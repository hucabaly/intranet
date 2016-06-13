<?php
namespace Rikkei\Core\Seeds;

use Illuminate\Database\Seeder;
use Rikkei\Core\Model\Menus;
use Rikkei\Core\Model\MenuItems;
use Illuminate\Support\Facades\Config;
use DB;

class MenuItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = Menus::getMenuDefault();
        if (! $menus) {
            return;
        }
        $menus = $menus->id;
        if (! file_exists(RIKKEI_CORE_PATH . 'config/menu.php')) {
            return;
        }
        $dataDemo = require RIKKEI_CORE_PATH . 'config/menu.php';
        if (! $dataDemo || ! count($dataDemo)) {
            return;
        }
        DB::beginTransaction();
        try {
            $this->createMenuItemsRecurive($dataDemo, null, 0, $menus);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    protected function createMenuItemsRecurive($data, $parentId, $sortOrder, $menuId)
    {
        foreach ($data as $item) {
            $dataChild = null;
            if (isset($item['child'] ) && count($item['child']) > 0) {
                $dataChild = $item['child'];
                unset($item['child']);
            }
            $dataItem = [];
            $dataItem = [
                'name' => '',
                'url' => '',
                'state' => 1,
                'menu_id' => $menuId,
                'sort_order' => $sortOrder,
            ];
            if (isset($item['label']) && $item['label']) {
                $dataItem['name'] = $item['label'];
            }
            if (isset($item['path']) && $item['path']) {
                $dataItem['url'] = $item['path'];
            }
            if (isset($item['active']) && $item['active']) {
                $dataItem['state'] = $item['active'];
            }
            if ($parentId) {
                $dataItem['parent_id'] = $parentId;
            }
            $menuItem = MenuItems::where('menu_id', $menuId)
                ->where('url', $dataItem['url'])
                ->first();
            if (! $menuItem) {
                $menuItem = new MenuItems();
            }
            $menuItem = $menuItem->setData($dataItem);
            $menuItem->save();
            if ($dataChild) {
                $this->createMenuItemsRecurive($dataChild, $menuItem->id, 0, $menuId);
            }
            $sortOrder++;
        }
    }
}

