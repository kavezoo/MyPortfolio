<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddMaintenanceSettings extends BaseMigration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $now = date('Y-m-d H:i:s');
        $rows = [
            [
                'name' => 'maintenance_mode',
                'label' => 'Karbantartás mód',
                'value' => '0',
                'pos' => 90,
            ],
            [
                'name' => 'maintenance_message',
                'label' => 'Karbantartás üzenet',
                'value' => 'Az oldal jelenleg karbantartás alatt áll.',
                'pos' => 91,
            ],
        ];

        foreach ($rows as $row) {
            $exists = $this->fetchRow(
                "SELECT id FROM settings WHERE name = '{$row['name']}' LIMIT 1",
            );
            if ($exists) {
                continue;
            }

            $label = addslashes($row['label']);
            $value = addslashes($row['value']);
            $this->execute(
                "INSERT INTO settings (name, label, value, visible, pos, created, modified)
                 VALUES ('{$row['name']}', '{$label}', '{$value}', 1, {$row['pos']}, '{$now}', '{$now}')",
            );
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->execute("DELETE FROM settings WHERE name IN ('maintenance_mode', 'maintenance_message')");
    }
}
