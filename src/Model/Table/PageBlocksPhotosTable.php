<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * PageBlocksPhotos Model
 *
 * @property \App\Model\Table\PageBlocksTable&\Cake\ORM\Association\BelongsTo $PageBlocks
 * @property \App\Model\Table\PhotosTable&\Cake\ORM\Association\BelongsTo $Photos
 *
 * @method \App\Model\Entity\PageBlocksPhoto newEmptyEntity()
 * @method \App\Model\Entity\PageBlocksPhoto newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\PageBlocksPhoto> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PageBlocksPhoto get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PageBlocksPhoto findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\PageBlocksPhoto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\PageBlocksPhoto> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PageBlocksPhoto|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\PageBlocksPhoto saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlocksPhoto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlocksPhoto>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlocksPhoto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlocksPhoto> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlocksPhoto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlocksPhoto>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageBlocksPhoto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageBlocksPhoto> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PageBlocksPhotosTable extends AppTable
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

        $this->setTable('page_blocks_photos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('CounterCache', [
            'PageBlocks' => ['photos_count'],
        ]);

        $this->belongsTo('PageBlocks', [
            'foreignKey' => 'page_block_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Photos', [
            'foreignKey' => 'photo_id',
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
            ->integer('photo_id')
            ->notEmptyString('photo_id');

        $validator
            ->scalar('css_class')
            ->maxLength('css_class', 100)
            ->allowEmptyString('css_class');

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
        $rules->add($rules->isUnique(['page_block_id', 'photo_id']), ['errorField' => 'page_block_id', 'message' => __('This combination of page_block_id and photo_id already exists')]);
        $rules->add($rules->existsIn(['page_block_id'], 'PageBlocks'), ['errorField' => 'page_block_id']);
        $rules->add($rules->existsIn(['photo_id'], 'Photos'), ['errorField' => 'photo_id']);

        return $rules;
    }
}
