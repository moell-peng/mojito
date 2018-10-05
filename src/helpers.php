<?php

if (!function_exists('request_intersect')) {
    /**
     * request intersect
     *
     * @param $keys
     * @return array|ø
     */
    function request_intersect($keys) {
        return array_filter(request()->only(is_array($keys) ? $keys : func_get_args()));
    }
}

if (!function_exists('make_tree')) {
    /**
     * @param array $list
     * @param int $parentId
     * @return array
     */
    function make_tree(array $list, $parentId = 0) {
        $tree = [];
        if (empty($list)) {
            return $tree;
        }

        $newList = [];
        foreach ($list as $k => $v) {
            $newList[$v['id']] = $v;
        }
        
        foreach ($newList as $value) {
            if ($parentId == $value['parent_id']) {
                $tree[] = &$newList[$value['id']];
            } elseif (isset($newList[$value['parent_id']])) {
                $newList[$value['parent_id']]['children'][] = &$newList[$value['id']];
            }
        }

        return $tree;
    }
}