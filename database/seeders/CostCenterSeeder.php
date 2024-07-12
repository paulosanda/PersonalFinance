<?php

namespace Database\Seeders;

use App\Models\CostCenter;
use Illuminate\Database\Seeder;

class CostCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $costCenters = [
            [
                'type' => CostCenter::TYPE_NEUTRAL,
                'cost_center_name' => 'não classificado',
            ],
            [
                'type' => CostCenter::TYPE_NEUTRAL,
                'cost_center_name' => 'outros',
            ],
            [
                'type' => CostCenter::TYPE_PERSONAL,
                'cost_center_name' => 'alimentação',
            ],
            [
                'type' => CostCenter::TYPE_PERSONAL,
                'cost_center_name' => 'habitação',
            ],
            [
                'type' => CostCenter::TYPE_PERSONAL,
                'cost_center_name' => 'transporte',
            ],
            [
                'type' => CostCenter::TYPE_PERSONAL,
                'cost_center_name' => 'saúde',
            ],
            [
                'type' => CostCenter::TYPE_PERSONAL,
                'cost_center_name' => 'educação',
            ],
            [
                'type' => CostCenter::TYPE_PERSONAL,
                'cost_center_name' => 'investimentos',
            ],
            [
                'type' => CostCenter::TYPE_PERSONAL,
                'cost_center_name' => 'vestuário',
            ],
            [
                'type' => CostCenter::TYPE_PERSONAL,
                'cost_center_name' => 'serviços',
            ],
            [
                'type' => CostCenter::TYPE_PERSONAL,
                'cost_center_name' => 'impostos e taxas',
            ],
            [
                'type' => CostCenter::TYPE_COMPANY,
                'cost_center_name' => 'operacional',
            ],
            [
                'type' => CostCenter::TYPE_COMPANY,
                'cost_center_name' => 'produção',
            ],
            [
                'type' => CostCenter::TYPE_COMPANY,
                'cost_center_name' => 'financeiro/legal',
            ],
            [
                'type' => CostCenter::TYPE_COMPANY,
                'cost_center_name' => 'salários',
            ],
            [
                'type' => CostCenter::TYPE_COMPANY,
                'cost_center_name' => 'marketing',
            ],
            [
                'type' => CostCenter::TYPE_COMPANY,
                'cost_center_name' => 'tecnologia/comunicação',
            ],
            [
                'type' => CostCenter::TYPE_COMPANY,
                'cost_center_name' => 'logistica',
            ],
            [
                'type' => CostCenter::TYPE_COMPANY,
                'cost_center_name' => 'dividendos',
            ],
        ];

        foreach ($costCenters as $costCenter) {
            CostCenter::create($costCenter);
        }
    }
}
