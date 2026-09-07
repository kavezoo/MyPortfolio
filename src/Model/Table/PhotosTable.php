<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Utility\Text;
use Cake\Validation\Validator;
use App\Service\PhotoFileService;
use ArrayObject;
use Cake\Datasource\EntityInterface;

/**
 * Photos Model
 *
 * @property \App\Model\Table\PhotoCategoriesTable&\Cake\ORM\Association\BelongsTo $PhotoCategories
 * @property \App\Model\Table\TagsTable&\Cake\ORM\Association\BelongsToMany $Tags
 * @property \App\Model\Table\BlogPostsTable&\Cake\ORM\Association\BelongsToMany $BlogPosts
 * @property \App\Model\Table\PageBlocksTable&\Cake\ORM\Association\BelongsToMany $PageBlocks
 *
 * @method \App\Model\Entity\Photo newEmptyEntity()
 * @method \App\Model\Entity\Photo newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Photo> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Photo get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Photo findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Photo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Photo> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Photo|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Photo saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Photo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Photo>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Photo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Photo> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Photo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Photo>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Photo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Photo> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\CounterCacheBehavior
 */
class PhotosTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('photos');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addTranslate([
            'title',
            'description',
            'location',
            'city',
        ]);

        $this->addBehavior('CounterCache', [
            'PhotoCategories' => ['photos_count'],
        ]);

        $this->belongsTo('PhotoCategories', [
            'foreignKey' => 'photo_category_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsToMany('Tags', [
            'foreignKey' => 'photo_id',
            'targetForeignKey' => 'tag_id',
            'through' => 'PhotosTags',
            'sort' => ['Tags.name' => 'ASC'],
        ]);
        $this->belongsToMany('BlogPosts', [
            'foreignKey' => 'photo_id',
            'targetForeignKey' => 'blog_post_id',
            'through' => 'BlogPostsPhotos',
        ]);
        $this->belongsToMany('PageBlocks', [
            'foreignKey' => 'photo_id',
            'targetForeignKey' => 'page_block_id',
            'through' => 'PageBlocksPhotos',
        ]);
    }

    /**
     * Ensure every photo gets a UUID before insert.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @param \Cake\Datasource\EntityInterface $entity Entity.
     * @param \ArrayObject $options Options.
     * @return void
     */
    public function beforeSave(EventInterface $event, $entity, $options): void
    {
        if ($entity->isNew() && empty($entity->uuid)) {
            $entity->uuid = Text::uuid();
        }
    }

    /**
     * Remove uploaded image (+ protect shield) after the DB delete is committed.
     *
     * @param \Cake\Event\EventInterface<\Cake\ORM\Table> $event Event.
     * @param \Cake\Datasource\EntityInterface $entity Photo entity.
     * @param \ArrayObject<string, mixed> $options Options.
     * @return void
     */
    public function afterDeleteCommit(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if (!$entity instanceof \App\Model\Entity\Photo) {
            return;
        }

        (new PhotoFileService())->deleteForPhoto($entity);
    }

    /**
     * Also run on afterDelete for non-atomic deletes / nested association cascades.
     *
     * @param \Cake\Event\EventInterface<\Cake\ORM\Table> $event Event.
     * @param \Cake\Datasource\EntityInterface $entity Photo entity.
     * @param \ArrayObject<string, mixed> $options Options.
     * @return void
     */
    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if (!$entity instanceof \App\Model\Entity\Photo) {
            return;
        }

        // Skip when the primary atomic delete will fire afterDeleteCommit next.
        if (!empty($options['atomic']) && !empty($options['_primary'])) {
            return;
        }

        (new PhotoFileService())->deleteForPhoto($entity);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('uuid')
            ->allowEmptyString('uuid');

        $validator
            ->integer('photo_category_id')
            ->notEmptyString('photo_category_id');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 100)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('code')
            ->maxLength('code', 50)
            ->allowEmptyString('code');

        $validator
            ->scalar('original_name')
            ->maxLength('original_name', 255)
            ->allowEmptyString('original_name');

        $validator
            ->scalar('filename')
            ->maxLength('filename', 255)
            ->requirePresence('filename', 'create')
            ->notEmptyString('filename');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('location')
            ->maxLength('location', 255)
            ->allowEmptyString('location');

        $validator
            ->scalar('city')
            ->maxLength('city', 100)
            ->allowEmptyString('city');

        $validator
            ->scalar('camera')
            ->maxLength('camera', 150)
            ->allowEmptyString('camera');

        $validator
            ->scalar('lens')
            ->maxLength('lens', 150)
            ->allowEmptyString('lens');

        $validator
            ->scalar('exposure')
            ->maxLength('exposure', 50)
            ->allowEmptyString('exposure');

        $validator
            ->scalar('aperture')
            ->maxLength('aperture', 50)
            ->allowEmptyString('aperture');

        $validator
            ->scalar('iso')
            ->maxLength('iso', 50)
            ->allowEmptyString('iso');

        $validator
            ->scalar('focal')
            ->maxLength('focal', 50)
            ->allowEmptyString('focal');

        $validator
            ->date('shot_date')
            ->allowEmptyDate('shot_date');

        $validator
            ->time('shot_time')
            ->allowEmptyTime('shot_time');

        $validator
            ->scalar('dimensions')
            ->maxLength('dimensions', 50)
            ->allowEmptyString('dimensions');

        $validator
            ->boolean('in_gallery')
            ->notEmptyString('in_gallery');

        $validator
            ->nonNegativeInteger('tags_count')
            ->notEmptyString('tags_count');

        $validator
            ->boolean('visible')
            ->notEmptyString('visible');

        $validator
            ->integer('pos')
            ->notEmptyString('pos');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['slug']), ['errorField' => 'slug']);
        $rules->add($rules->isUnique(['uuid']), ['errorField' => 'uuid']);
        $rules->add($rules->existsIn(['photo_category_id'], 'PhotoCategories'), ['errorField' => 'photo_category_id']);

        return $rules;
    }

    /**
     * Gallery photos (non-panorama images marked for the gallery).
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findGallery(SelectQuery $query): SelectQuery
    {
        return $query
            ->find('visible')
            ->where(['Photos.in_gallery' => true])
            ->contain(['Tags', 'PhotoCategories']);
    }

    /**
     * Panorama photos.
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findPanoramas(SelectQuery $query): SelectQuery
    {
        return $query
            ->find('visible')
            ->contain(['Tags', 'PhotoCategories'])
            ->matching('PhotoCategories', function (SelectQuery $q) {
                return $q->where(['PhotoCategories.slug' => 'panorama']);
            });
    }

    /**
     * Find a visible photo by UUID.
     *
     * @param string $uuid Photo UUID.
     * @return \App\Model\Entity\Photo
     */
    public function getByUuid(string $uuid): \App\Model\Entity\Photo
    {
        /** @var \App\Model\Entity\Photo $photo */
        $photo = $this->find()
            ->where(['Photos.uuid' => $uuid, 'Photos.visible' => true])
            ->contain(['Tags', 'PhotoCategories'])
            ->firstOrFail();

        return $photo;
    }
}
