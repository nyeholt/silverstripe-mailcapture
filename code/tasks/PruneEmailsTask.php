<?php

/**
 * @author <marcus@silverstripe.com.au>
 * @license BSD License http://www.silverstripe.org/bsd-license
 */
class PruneEmailsTask extends BuildTask
{
    public function run($request)
    {
        if (Permission::check('ADMIN')) {
            $since = date('Y-m-d H:i:s', strtotime('-1 month'));
            $list = CapturedEmail::get()->filter('Created:LessThan', $since);
            echo "Deleting " . $list->count() . " captured emails (if confirm flag set)<br/>\n";

            if ($request->getVar('confirm')) {
                $list->removeAll();
            }
        }
    }
}
