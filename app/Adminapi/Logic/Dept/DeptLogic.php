<?php

namespace App\Adminapi\Logic\Dept;

use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Dept\Dept;

/**
 * 部门管理逻辑
 */
class DeptLogic extends BaseLogic
{
    /**
     * @notes 部门列表
     * @param $params
     * @return array
     */
    public static function lists($params)
    {
        $where = [];
        if (!empty($params['name'])) {
            $where[] = ['name', 'like', '%' . $params['name'] . '%'];
        }
        if (isset($params['status']) && $params['status'] != '') {
            $where[] = ['status', '=', $params['status']];
        }
        $lists = Dept::where($where)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        $pid = 0;
        if (!empty($lists)) {
            $pid = min(array_column($lists, 'pid'));
        }
        return self::getTree($lists, $pid);
    }

    /**
     * @notes 列表树状结构
     * @param $array
     * @param int $pid
     * @param int $level
     * @return array
     */
    public static function getTree($array, $pid = 0, $level = 0)
    {
        $list = [];
        foreach ($array as $key => $item) {
            if ($item['pid'] == $pid) {
                $item['level'] = $level;
                $item['children'] = self::getTree($array, $item['id'], $level + 1);
                $list[] = $item;
            }
        }
        return $list;
    }

    /**
     * @notes 上级部门 TODO 这个用不到
     * @return array
     */
    public static function leaderDept()
    {
        $lists = Dept::select(['id', 'name'])
            ->where(['status' => 1])
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        return $lists;
    }

    /**
     * @notes 添加部门
     * @param array $params
     */
    public static function add(array $params)
    {
        Dept::create([
            'pid' => $params['pid'],
            'name' => $params['name'],
            'leader' => $params['leader'] ?? '',
            'mobile' => $params['mobile'] ?? '',
            'status' => $params['status'],
            'sort' => $params['sort'] ?? 0,
        ]);
    }

    /**
     * @notes 编辑部门
     * @param array $params
     * @return bool
     */
    public static function edit(array $params): bool
    {
        try {
            $pid = $params['pid'];
            $oldDeptData = Dept::query()->find($params['id']);
            if ($oldDeptData && $oldDeptData->pid == 0) {
                $pid = 0;
            }

            Dept::where('id', $params['id'])->update([
                'pid' => $pid,
                'name' => $params['name'],
                'leader' => $params['leader'] ?? '',
                'mobile' => $params['mobile'] ?? '',
                'status' => $params['status'],
                'sort' => $params['sort'] ?? 0,
            ]);
            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 删除部门
     * @param array $params
     */
    public static function delete(array $params)
    {
        Dept::destroy($params['id']);
    }

    /**
     * @notes 获取部门详情
     * @param $params
     * @return array
     */
    public static function detail($params): array
    {
        return Dept::find($params['id'])->toArray();
    }

    /**
     * @notes 部门数据
     * @return array
     */
    public static function getAllData()
    {
        $data = Dept::where(['status' => YesNoEnum::YES])
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        $pid = min(array_column($data, 'pid'));
        return self::getTree($data, $pid);
    }
}
