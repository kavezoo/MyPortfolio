<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * PageBlockItems Model
 *
 * @property \App\Model\Table\PageBlocksTable&\Cake\ORM\Association\BelongsTo $PageBlocks
 *
 * @method \App\Model\Entity\PageBlockItem newEmptyEntity()
 * @method \App\Model\Entity\PageBlockItem newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\PageBlockItem> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PageBlockItem get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PageBlockItem findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\PageBlockItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\PageBlockItem> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PageBlockItem|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\PageBlockItem saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlockItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlockItem>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlockItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlockItem> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlockItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlockItem>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlockItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlockItem> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PageBlockItemsTable extends AppTable
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

        $this->setTable('page_block_items');
        $this->setDisplayField('item_type');
        $this->setPrimaryKey('id');

        $this->addTranslate(['body']);

        $this->addBehavior('CounterCache', [
            'PageBlocks' => ['page_block_items_count'],
        ]);

        $this->belongsTo('PageBlocks', [
            'foreignKey' => 'page_block_id',
            'joinType' => 'INNER',
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
            ->integer('page_block_id')
            ->notEmptyString('page_block_id');

        $validator
            ->scalar('item_type')
            ->maxLength('item_type', 50)
            ->notEmptyString('item_type');

        $validator
            ->scalar('body')
            ->requirePresence('body', 'create')
            ->notEmptyString('body');

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->allowEmptyString('url');

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
        $rules->add($rules->existsIn(['page_block_id'], 'PageBlocks'), ['errorField' => 'page_block_id']);

        return $rules;
    }
}
