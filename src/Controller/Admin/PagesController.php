<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Pages Controller
 *
 * @property \App\Model\Table\PagesTable $Pages
 */
class PagesController extends AppController
{
    /**
     * Initialize controller
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $queryParams = $this->getRequest()->getQueryParams();

        // Szülő-szűrés kikapcsolása: ?clear=filter → teljes lista, session törlése
        if (isset($queryParams['clear']) && $queryParams['clear'] === 'filter') {
            $this->session->delete('Paging.Pages.params');

            return $this->redirect(['action' => 'index']);
        }

        // Keresés és szűrők törlése gomb kezelése (?clear=search)
        if (isset($queryParams['clear']) && $queryParams['clear'] === 'search') {
            $this->session->delete('Paging.Pages.params');

            $redirectParams = $queryParams;
            unset($redirectParams['clear'], $redirectParams['search'], $redirectParams['page']);

            return $this->redirect([
                'action' => 'index',
                '?' => $redirectParams ?: null,
            ]);
        }

        // =========================================================================
        // ⚙️ KERESÉSI BEÁLLÍTÁSOK (Itt kapcsold be/ki a kívánt keresési mezőket)
        // =========================================================================
        $searchableFields = [
            // --- 1. Saját tábla (Pages) mezői (sütéskor: string/uuid oszlopok) ---
            // 'Pages.id',
            'Pages.slug',
            'Pages.menu_label',
            'Pages.url',
            'Pages.title',
            'Pages.hero_title',
            'Pages.hero_lead',
            'Pages.template',
            // --- 2. Kapcsolt (BelongsTo) táblák megjelenítő mezői ---
            'HeroPhotos.title',
        ];

        // =========================================================================
        // ⚙️ LAPOZÓ BEÁLLÍTÁSA
        // =========================================================================
        $this->paginate = [
            'limit' => 20,
            'maxLimit' => 100,
        ];

        // Ha üres az URL, de a Sessionben van érvényes mentett állapot, oda irányítunk vissza
        // (szülő-szűrt lista NEM áll vissza automatikusan – csak explicit URL-ből)
        if (empty($queryParams) && $this->session->check('Paging.Pages.params')) {
            $savedParams = (array)$this->session->read('Paging.Pages.params');
            if (!empty($savedParams) && !array_key_exists('parent_filter', $savedParams)) {
                return $this->redirect([
                    'action' => 'index',
                    '?' => $savedParams,
                ]);
            }
        }

        $query = $this->fetchTable('Pages')->find()
            ->contain(['HeroPhotos']);

        // =========================================================================
        // 🔗 SZÜLŐ REKORD SZERINTI SZŰRÉS (gyerek lista a szülő index gombjából)
        // Csak ?parent_filter=... + a hozzá tartozó FK paraméter együtt esetén aktív.
        // =========================================================================
        $parentContext = null;
        $parentFilter = isset($queryParams['parent_filter']) ? (string)$queryParams['parent_filter'] : null;
        $table = $this->fetchTable('Pages');
        foreach ($table->associations()->getByType('BelongsTo') as $association) {
            if ($parentFilter === null || $parentFilter !== $association->getName()) {
                continue;
            }

            $foreignKey = $association->getForeignKey();
            if (!is_string($foreignKey)) {
                continue;
            }

            $filterValue = $queryParams[$foreignKey] ?? null;
            if ($filterValue === null || $filterValue === '') {
                continue;
            }

            $query->where(['Pages.' . $foreignKey => $filterValue]);

            $parentTable = $association->getTarget();
            try {
                $parentEntity = $this->fetchTable($parentTable->getAlias())->get($filterValue);
            } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
                throw new \Cake\Http\Exception\NotFoundException(__('Parent record not found.'));
            }

            $displayField = $parentTable->getDisplayField();
            $parentContext = [
                'association' => $association->getName(),
                'foreignKey' => $foreignKey,
                'foreignKeyValue' => $filterValue,
                'parentFilter' => $parentFilter,
                'controller' => $association->getName(),
                'displayField' => $displayField,
                'label' => (string)($parentEntity->{$displayField} ?? $filterValue),
            ];
            break;
        }

        // =========================================================================
        // 🔍 KERESÉS VÉGREHAJTÁSA A KONFIGURÁLT MEZŐK ALAPJÁN
        // =========================================================================
        $search = trim((string)($queryParams['search'] ?? ''));
        if ($search !== '' && !empty($searchableFields)) {
            $searchLike = '%' . $search . '%';
            $conditions = [];

            foreach ($searchableFields as $field) {
                $conditions[$field . ' LIKE'] = $searchLike;
            }

            $query->where(['OR' => $conditions]);
        }

        // =========================================================================
        // 📄 LAPOZÁS VÉGREHAJTÁSA HIBAKEZELÉSSEL
        // =========================================================================
        try {
            $pages = $this->paginate($query);

            if (!empty($queryParams)) {
                $this->session->write('Paging.Pages.params', $queryParams);
            } elseif ($parentContext === null) {
                $this->session->delete('Paging.Pages.params');
            }
        } catch (\Cake\Http\Exception\NotFoundException $e) {
            $this->Flash->warning(__('Page not found. Redirecting to the first page.'), ['plugin' => 'KvAdmin']);

            $fallbackParams = $queryParams;
            unset($fallbackParams['page']);

            $this->session->write('Paging.Pages.params', $fallbackParams);

            return $this->redirect([
                'action' => 'index',
                '?' => $fallbackParams,
            ]);
        }

        // Utoljára megtekintett / szerkesztett rekord visszagörgetésének támogatása
        $lastViewedId = $this->session->read('LastViewed.page_id');
        $scrollToId = $this->session->read('ScrollTo.page_id') ?? $lastViewedId;

        $this->set(compact('pages', 'lastViewedId', 'scrollToId', 'search', 'parentContext'));
    }
    /**
     * View method
     *
     * @param string|null $id Page id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $page = $this->Pages->get($id, contain: ['HeroPhotos', 'Pages_menu_label_translation', 'Pages_title_translation', 'Pages_meta_description_translation', 'Pages_hero_title_translation', 'Pages_hero_lead_translation', 'Pages_body_translation', 'I18n', 'PageBlocks']);
		$this->session->write('LastViewed.Admin.page_id', (int)$id ?? 0);
		$this->session->write('ScrollTo.Admin.page_id', (int)$id ?? 0);
        $this->set(compact('page'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $page = $this->fetchTable('Pages')->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $data = $this->getRequest()->getData();
            $page = $this->fetchTable('Pages')->patchEntity($page, $data);
            if ($this->fetchTable('Pages')->save($page)) {
                $this->Flash->success(__('The {0} has been saved.'), __('page'), ['plugin' => 'KvAdmin']);

                // Frissen létrehozott rekord megjelölése visszagörgetéshez az index nézetben
                $this->session->write('ScrollTo.Admin.page.id', $page->id ?? 0);
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Could not save data. Please review the errors and try again.'), ['plugin' => 'KvAdmin']);
        }
        $heroPhotos = $this->fetchTable('Pages')->HeroPhotos->find('list', limit: 200)->all();
        $this->set(compact('page', 'heroPhotos'));
    }
    /**
     * Edit method
     *
     * @param string|null $id Page id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $page = $this->fetchTable('Pages')->get($id, contain: []);
		$this->session->write('LastViewed.Admin.page_id', (int)$id ?? 0);
		$this->session->write('ScrollTo.Admin.page_id', (int)$id ?? 0);

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $data = $this->getRequest()->getData();
            $page = $this->fetchTable('Pages')->patchEntity($page, $data);
            if ($this->fetchTable('Pages')->save($page)) {
                $this->Flash->success(__('The {0} has been saved.', __('page')), ['plugin' => 'KvAdmin']);

                $redirectParams = (array)$this->session->read('Paging.Admin.Pages.params');
                return $this->redirect([
                    'action' => 'index',
                    '?' => $redirectParams,
                ]);
            }
            $this->Flash->error(__('Could not save data. Please review the errors and try again.'), ['plugin' => 'KvAdmin']);
        }
        $heroPhotos = $this->fetchTable('Pages')->HeroPhotos->find('list', limit: 200)->all();
        $this->set(compact('page', 'heroPhotos'));
    }
    /**
     * Delete method
     *
     * @param string|null $id Page id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        
        $table = $this->fetchTable('Pages');
        $page = $table->get($id);
		$pageName = $page->name;

        $this->session->delete('LastViewed.page_id');

        // Törlés utáni visszagörgetés: megkeressük a közvetlenül előtte lévő rekordot
        $neighbor = $table->find()
            ->select(['id'])
            ->where(['id <' => (int)$id])
            ->orderByDesc('id')
            ->first();

        // Ha nincs előtte lévő rekord (első volt), megpróbáljuk a következőt keresni
        if (!$neighbor) {
            $neighbor = $table->find()
                ->select(['id'])
                ->where(['id >' => (int)$id])
                ->orderByAsc('id')
                ->first();
        }

        if ($neighbor) {
            $this->session->write('ScrollTo.page_id', (int)$neighbor->id);
        } else {
            $this->session->delete('ScrollTo.page_id');
        }

        if ($table->delete($page)) {
            $this->Flash->success(__('The {0} has been successfully deleted.', __('page')), ['plugin' => 'KvAdmin']);
        } else {
            $this->Flash->error(__('Could not delete the record. Please try again.'), ['plugin' => 'KvAdmin']);
        }

        $redirectParams = (array)$this->session->read('Paging.Pages.params');
        return $this->redirect([
            'action' => 'index',
            '?' => $redirectParams,
        ]);
    }}
