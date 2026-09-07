<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * PageBlocks Model
 *
 * @property \App\Model\Table\PagesTable&\Cake\ORM\Association\BelongsTo $Pages
 * @property \App\Model\Table\PhotosTable&\Cake\ORM\Association\BelongsTo $FeaturedPhotos
 * @property \App\Model\Table\PageBlockItemsTable&\Cake\ORM\Association\HasMany $PageBlockItems
 * @property \App\Model\Table\PhotosTable&\Cake\ORM\Association\BelongsToMany $Photos
 *
 * @method \App\Model\Entity\PageBlock newEmptyEntity()
 * @method \App\Model\Entity\PageBlock newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\PageBlock> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PageBlock get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PageBlock findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\PageBlock patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\PageBlock> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PageBlock|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\PageBlock saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlock>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlock>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlock>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlock> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlock>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlock>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlock>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlock> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PageBlocksTable extends AppTable
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

        $this->setTable('page_blocks');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addTranslate([
            'title',
            'lead',
            'body',
            'quote',
            'button_label',
        ]);

        $this->addBehavior('CounterCache', [
            'Pages' => ['page_blocks_count'],
        ]);

        $this->belongsTo('Pages', [
            'foreignKey' => 'page_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FeaturedPhotos', [
            'foreignKey' => 'photo_id',
            'className' => 'Photos',
        ]);
        $this->hasMany('PageBlockItems', [
            'foreignKey' => 'page_block_id',
            'sort' => ['PageBlockItems.pos' => 'ASC', 'PageBlockItems.id' => 'ASC'],
        ]);
        $this->belongsToMany('Photos', [
            'foreignKey' => 'page_block_id',
            'targetForeignKey' => 'photo_id',
            'through' => 'PageBlocksPhotos',
            'sort' => ['PageBlocksPhotos.pos' => 'ASC', 'PageBlocksPhotos.id' => 'ASC'],
        ]);
        $this->hasMany('PageBlocksPhotos', [
            'foreignKey' => 'page_block_id',
        ]);
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
            ->integer('page_id')
            ->notEmptyString('page_id');

        $validator
            ->scalar('block_type')
            ->maxLength('block_type', 50)
            ->requirePresence('block_type', 'create')
            ->notEmptyString('block_type');

        $validator
            ->scalar('layout')
            ->maxLength('layout', 50)
            ->allowEmptyString('layout');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmptyString('title');

        $validator
            ->scalar('lead')
            ->maxLength('lead', 255)
            ->allowEmptyString('lead');

        $validator
            ->scalar('body')
            ->allowEmptyString('body');

        $validator
            ->scalar('quote')
            ->allowEmptyString('quote');

        $validator
            ->scalar('button_label')
            ->maxLength('button_label', 100)
            ->allowEmptyString('button_label');

        $validator
            ->scalar('button_url')
            ->maxLength('button_url', 255)
            ->allowEmptyString('button_url');

        $validator
            ->integer('photo_id')
            ->allowEmptyString('photo_id');

        $validator
            ->nonNegativeInteger('page_block_items_count')
            ->notEmptyString('page_block_items_count');

        $validator
            ->nonNegativeInteger('photos_count')
            ->notEmptyString('photos_count');

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
        $rules->add($rules->existsIn(['page_id'], 'Pages'), ['errorField' => 'page_id']);
        $rules->add($rules->existsIn(['photo_id'], 'FeaturedPhotos'), ['errorField' => 'photo_id']);

        return $rules;
    }
}
