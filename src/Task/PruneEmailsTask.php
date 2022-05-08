<?php

namespace Symbiote\MailCapture\BuildTask;

use SilverStripe\Dev\BuildTask;
use SilverStripe\Security\Permission;
use Symbiote\MailCapture\Model\CapturedEmail;

/**
 * @author <marcus@silverstripe.com.au>
 * @license BSD License http://www.silverstripe.org/bsd-license
 */
class PruneEmailsTask extends BuildTask
{
    public function run($request) {
        if (Permission::check('ADMIN')) {
            $since = date('Y-m-d H:i:s', strtotime('-1 month'));
            $list = CapturedEmail::get()->filter('Created:LessThan', $since);
            echo "Deleting " . $list->count() . " captured emails (if ?confirm get var is set)<br/>\n";

            if ($request->getVar('confirm')) {
                $list->removeAll();
            }
        }
    }
}
