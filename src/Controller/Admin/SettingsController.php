<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Settings Controller
 *
 * @property \App\Model\Table\SettingsTable $Settings
 */
class SettingsController extends AppController
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
            $this->session->delete('Paging.Settings.params');

            return $this->redirect(['action' => 'index']);
        }

        // Keresés és szűrők törlése gomb kezelése (?clear=search)
        if (isset($queryParams['clear']) && $queryParams['clear'] === 'search') {
            $this->session->delete('Paging.Settings.params');

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
            // --- 1. Saját tábla (Settings) mezői (sütéskor: string/uuid oszlopok) ---
            // 'Settings.id',
            'Settings.name',
            'Settings.label',
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
        if (empty($queryParams) && $this->session->check('Paging.Settings.params')) {
            $savedParams = (array)$this->session->read('Paging.Settings.params');
            if (!empty($savedParams) && !array_key_exists('parent_filter', $savedParams)) {
                return $this->redirect([
                    'action' => 'index',
                    '?' => $savedParams,
                ]);
            }
        }

        $query = $this->fetchTable('Settings')->find();

        // =========================================================================
        // 🔗 SZÜLŐ REKORD SZERINTI SZŰRÉS (gyerek lista a szülő index gombjából)
        // Csak ?parent_filter=... + a hozzá tartozó FK paraméter együtt esetén aktív.
        // =========================================================================
        $parentContext = null;
        $parentFilter = isset($queryParams['parent_filter']) ? (string)$queryParams['parent_filter'] : null;
        $table = $this->fetchTable('Settings');
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

            $query->where(['Settings.' . $foreignKey => $filterValue]);

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
            $settings = $this->paginate($query);

            if (!empty($queryParams)) {
                $this->session->write('Paging.Settings.params', $queryParams);
            } elseif ($parentContext === null) {
                $this->session->delete('Paging.Settings.params');
            }
        } catch (\Cake\Http\Exception\NotFoundException $e) {
            $this->Flash->warning(__('Page not found. Redirecting to the first page.'), ['plugin' => 'KvAdmin']);

            $fallbackParams = $queryParams;
            unset($fallbackParams['page']);

            $this->session->write('Paging.Settings.params', $fallbackParams);

            return $this->redirect([
                'action' => 'index',
                '?' => $fallbackParams,
            ]);
        }

        // Utoljára megtekintett / szerkesztett rekord visszagörgetésének támogatása
        $lastViewedId = $this->session->read('LastViewed.setting_id');
        $scrollToId = $this->session->read('ScrollTo.setting_id') ?? $lastViewedId;

        $this->set(compact('settings', 'lastViewedId', 'scrollToId', 'search', 'parentContext'));
    }
    /**
     * View method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $setting = $this->Settings->get($id, contain: ['Settings_label_translation', 'Settings_value_translation', 'I18n']);
		$this->session->write('LastViewed.Admin.setting_id', (int)$id ?? 0);
		$this->session->write('ScrollTo.Admin.setting_id', (int)$id ?? 0);
        $this->set(compact('setting'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $setting = $this->Settings->newEmptyEntity();
        if ($this->request->is('post')) {
            $setting = $this->patchWithTranslations($this->Settings, $setting, $this->request->getData());
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The {0} has been saved.'), __('setting'), ['plugin' => 'KvAdmin']);

                // Frissen létrehozott rekord megjelölése visszagörgetéshez az index nézetben
                $this->session->write('ScrollTo.Admin.setting.id', $setting->id ?? 0);
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Could not save data. Please review the errors and try again.'), ['plugin' => 'KvAdmin']);
        }
        $this->set(compact('setting'));
    }
    /**
     * Edit method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setting = $this->getWithTranslations($this->Settings, $id);
		$this->session->write('LastViewed.Admin.setting_id', (int)$id ?? 0);
		$this->session->write('ScrollTo.Admin.setting_id', (int)$id ?? 0);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $setting = $this->patchWithTranslations($this->Settings, $setting, $this->request->getData());
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The {0} has been saved.', __('setting')), ['plugin' => 'KvAdmin']);

                $redirectParams = (array)$this->session->read('Paging.Admin.Settings.params');
                return $this->redirect([
                    'action' => 'index',
                    '?' => $redirectParams,
                ]);
            }
            $this->Flash->error(__('Could not save data. Please review the errors and try again.'), ['plugin' => 'KvAdmin']);
        }
        $this->set(compact('setting'));
    }
    /**
     * Delete method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        
        $table = $this->fetchTable('Settings');
        $setting = $table->get($id);
		$settingName = $setting->name;

        $this->session->delete('LastViewed.setting_id');

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
            $this->session->write('ScrollTo.setting_id', (int)$neighbor->id);
        } else {
            $this->session->delete('ScrollTo.setting_id');
        }

        if ($table->delete($setting)) {
            $this->Flash->success(__('The {0} has been successfully deleted.', __('setting')), ['plugin' => 'KvAdmin']);
        } else {
            $this->Flash->error(__('Could not delete the record. Please try again.'), ['plugin' => 'KvAdmin']);
        }

        $redirectParams = (array)$this->session->read('Paging.Settings.params');
        return $this->redirect([
            'action' => 'index',
            '?' => $redirectParams,
        ]);
    }}
