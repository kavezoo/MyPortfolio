<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * BlogPostsPhotos Model
 *
 * @property \App\Model\Table\BlogPostsTable&\Cake\ORM\Association\BelongsTo $BlogPosts
 * @property \App\Model\Table\PhotosTable&\Cake\ORM\Association\BelongsTo $Photos
 *
 * @method \App\Model\Entity\BlogPostsPhoto newEmptyEntity()
 * @method \App\Model\Entity\BlogPostsPhoto newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\BlogPostsPhoto> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostsPhoto get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\BlogPostsPhoto findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\BlogPostsPhoto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\BlogPostsPhoto> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPostsPhoto|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\BlogPostsPhoto saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\BlogPostsPhoto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlogPostsPhoto>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BlogPostsPhoto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlogPostsPhoto> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BlogPostsPhoto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlogPostsPhoto>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BlogPostsPhoto>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlogPostsPhoto> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BlogPostsPhotosTable extends AppTable
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

        $this->setTable('blog_posts_photos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('CounterCache', [
            'BlogPosts' => ['photos_count'],
        ]);

        $this->belongsTo('BlogPosts', [
            'foreignKey' => 'blog_post_id',
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
            ->integer('blog_post_id')
            ->notEmptyString('blog_post_id');

        $validator
            ->integer('photo_id')
            ->notEmptyString('photo_id');

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
        $rules->add($rules->isUnique(['blog_post_id', 'photo_id']), ['errorField' => 'blog_post_id', 'message' => __('This combination of blog_post_id and photo_id already exists')]);
        $rules->add($rules->existsIn(['blog_post_id'], 'BlogPosts'), ['errorField' => 'blog_post_id']);
        $rules->add($rules->existsIn(['photo_id'], 'Photos'), ['errorField' => 'photo_id']);

        return $rules;
    }
}
