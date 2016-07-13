<?php
defined('JPATH_BASE') or die();


jimport('joomla.form.formfield');
class JFormFieldHostedCallBackUrls extends JFormField
{
    var $type = 'hostedcallbackurls';

    public function __construct($form = null)
    {
        parent::__construct($form);
    }

    private function getUrl($params = array())
    {
        $url = JURI::base();

        $url = str_replace('/administrator','',$url);

        if(!empty($params)) {
            $url = $url.'?'.http_build_query($params);
        }

        return $url;
    }

    protected function getInput()
    {
        $html = '<p>Please savemethod to get callback urls</p>';

        $cid = $_GET['cid'];
        if(!empty($cid)) {

            $callbackUrl = $this->getUrl(array(
                //option=com_virtuemart&view=pluginresponse&task=pluginnotification&tmpl=component&pm=3
                'option' => 'com_virtuemart',
                'view' => 'pluginresponse',
                'task' => 'pluginnotification',
                'tmpl' => 'component',
                'pm' => current($cid),
            ));

            $mnsUrl = $this->getUrl(array(
                //index.php?option=com_virtuemart&view=plugin&type=vmpayment&name=upg&action=mnssave
                'option' => 'com_virtuemart',
                'view' => 'plugin',
                'type' => 'vmpayment',
                'name' => 'hostedpayments',
                'action' => 'mnssave',
            ));

            $mnsCronUrl = $this->getUrl(array(
                //index.php?option=com_virtuemart&view=plugin&type=vmpayment&name=upg&action=mnssave
                'option' => 'com_virtuemart',
                'view' => 'plugin',
                'type' => 'vmpayment',
                'name' => 'hostedpayments',
                'action' => 'mnsprocess',
            ));

            $callbackUrlLabel = vmText::_('VMPAYMENT_UPG_PAYCO_CALLBACK_URL_URL_LABEL');
            $mnsSaveUrlLabel = vmText::_('VMPAYMENT_UPG_PAYCO_MNS_URL_URL_LABEL');
            $mnsCronUrlLabel = vmText::_('VMPAYMENT_UPG_PAYCO_MNS_URL_URL_CRON_LABEL');

            $html = "<dl>";
            $html .= "<dt>{$callbackUrlLabel}</dt><dd>{$callbackUrl}</dd>";
            $html .= "<dt>{$mnsSaveUrlLabel}</dt><dd>{$mnsUrl}</dd>";
            $html .= "<dt>{$mnsCronUrlLabel}</dt><dd>{$mnsCronUrl}</dd>";
            $html .= "</dl>";
        }

        return $html;
    }
}