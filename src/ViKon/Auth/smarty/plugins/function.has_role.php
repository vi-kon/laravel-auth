<?php


if (!function_exists('smarty_function_has_role')) {
    /**
     * @param array $params
     * @param Smarty_Internal_Template $smarty
     *
     * @throws SmartyException
     * @return mixed
     *
     * @author Kovács Vince
     */
    function smarty_function_has_role($params, Smarty_Internal_Template &$smarty) {
        if (!isset($params['role'])) {
            throw new SmartyException('Missing role attribute for has_role tag');
        }

        return \AuthUser::hasRole($params['role']);
    }
}
