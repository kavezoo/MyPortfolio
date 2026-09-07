<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Pages Model
 *
 * @property \App\Model\Table\PhotosTable&\Cake\ORM\Association\BelongsTo $HeroPhotos
 * @property \App\Model\Table\PageBlocksTable&\Cake\ORM\Association\HasMany $PageBlocks
 *
 * @method \App\Model\Entity\Page newEmptyEntity()
 * @method \App\Model\Entity\Page newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Page> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Page get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Page findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Page patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Page> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Page|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Page saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Page>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Page>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Page>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Page> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Page>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Page>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Page>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Page> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PagesTable extends AppTable
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

        $this->setTable('pages');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addTranslate([
            'menu_label',
            'title',
            'meta_description',
            'hero_title',
            'hero_lead',
            'body',
        ]);

        $this->belongsTo('HeroPhotos', [
            'foreignKey' => 'hero_photo_id',
            'className' => 'Photos',
        ]);
        $this->hasMany('PageBlocks', [
            'foreignKey' => 'page_id',
            'sort' => ['PageBlocks.pos' => 'ASC', 'PageBlocks.id' => 'ASC'],
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
            ->scalar('slug')
            ->maxLength('slug', 100)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('menu_label')
            ->maxLength('menu_label', 100)
            ->allowEmptyString('menu_label');

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->requirePresence('url', 'create')
            ->notEmptyString('url');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('meta_description')
            ->allowEmptyString('meta_description');

        $validator
            ->scalar('hero_title')
            ->maxLength('hero_title', 255)
            ->allowEmptyString('hero_title');

        $validator
            ->scalar('hero_lead')
            ->maxLength('hero_lead', 255)
            ->allowEmptyString('hero_lead');

        $validator
            ->integer('hero_photo_id')
            ->allowEmptyString('hero_photo_id');

        $validator
            ->scalar('body')
            ->allowEmptyString('body');

        $validator
            ->scalar('template')
            ->maxLength('template', 50)
            ->notEmptyString('template');

        $validator
            ->nonNegativeInteger('page_blocks_count')
            ->notEmptyString('page_blocks_count');

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
        $rules->add($rules->existsIn(['hero_photo_id'], 'HeroPhotos'), ['errorField' => 'hero_photo_id']);

        return $rules;
    }

    /**
     * Visible pages listed in the main navigation.
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findMenu(SelectQuery $query): SelectQuery
    {
        return $query
            ->find('visible')
            ->where([
                'Pages.menu_label IS NOT' => null,
                'Pages.menu_label !=' => '',
            ]);
    }

    /**
     * Load a published page with its blocks, items and photos.
     *
     * @param string $slug Page slug.
     * @return \App\Model\Entity\Page
     */
    public function getBySlug(string $slug): \App\Model\Entity\Page
    {
        /** @var \App\Model\Entity\Page $page */
        $page = $this->find('visible')
            ->contain([
                'HeroPhotos' => ['Tags'],
                'PageBlocks' => function (SelectQuery $q) {
                    return $q
                        ->find('visible')
                        ->contain([
                            'FeaturedPhotos' => ['Tags'],
                            'PageBlockItems' => function (SelectQuery $items) {
                                return $items->find('visible');
                            },
                            'Photos' => [
                                'Tags',
                            ],
                        ]);
                },
            ])
            ->where(['Pages.slug' => $slug])
            ->firstOrFail();

        return $page;
    }
}
