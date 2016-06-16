<?php
namespace Rikkei\Core\Seeds;

use Illuminate\Database\Seeder;
use Rikkei\Core\Model\Menus;
use Rikkei\Core\Model\MenuItems;
use Rikkei\Team\Model\Action;
use DB;

class MenuItemsSeeder extends Seeder
{
    
    protected $menuDefaultId = null;
    protected $menuSettingId = null;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menus::getMenuDefault();
        if ($menu) {
            $this->menuDefaultId = $menu->id;
        }
        $menu = Menus::getMenuSetting();
        if ($menu) {
            $this->menuSettingId = $menu->id;
        }
        
        if (! file_exists(RIKKEI_CORE_PATH . 'config/menu.php')) {
            return;
        }
        $dataDemo = require RIKKEI_CORE_PATH . 'config/menu.php';
        if (! $dataDemo || ! count($dataDemo)) {
            return;
        }
        DB::beginTransaction();
        try {
            $this->createMenuItemsRecurive($dataDemo, null, 0, $this->menuDefaultId);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    protected function createMenuItemsRecurive($data, $parentId, $sortOrder, $menuId)
    {
        foreach ($data as $key => $item) {
            if ($key == 'setting') {
                $menuId = $this->menuSettingId;
            }
            if (! $menuId) {
                continue;
            }
            $dataChild = null;
            if (isset($item['child'] ) && count($item['child']) > 0) {
                $dataChild = $item['child'];
                unset($item['child']);
            }
            $dataItem = [
                'name' => '',
                'url' => '',
                'state' => 1,
                'menu_id' => $menuId,
                'sort_order' => $sortOrder,
                'parent_id' => $parentId,
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
            if (isset($item['action_code']) && $item['action_code']) {
                $actionPermission = Action::where('name', $item['action_code'])->first();
                if ($actionPermission) {
                    $dataItem['action_id'] = $actionPermission->id;
                }
            }
            if ($key == 'setting') {
                $menuItem = new MenuItems();
            } else {
                $menuItem = MenuItems::where('menu_id', $menuId)
                    ->where('url', $dataItem['url'])
                    ->where('name', $dataItem['name'])
                    ->first();
                if (! $menuItem) {
                    $menuItem = new MenuItems();
                    $menuItem->setData($dataItem);
                    $menuItem->save();
                }
            }
            if ($dataChild) {
                $this->createMenuItemsRecurive($dataChild, $menuItem->id, 0, $menuId);
            }
            $sortOrder++;
        }
    }
}

