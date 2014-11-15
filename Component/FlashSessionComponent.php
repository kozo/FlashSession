<?php
App::uses('SessionComponent', 'Controller/Component');

class FlashSessionComponent extends SessionComponent {

    const FLASH_DIR_NAME = 'Flash';
    const EXT = '.ctp';

    public function __call($methodName, $arguments)
    {
        if (strpos($methodName, 'setFlash') !== 0) {
            trigger_error('Call to undefined method ' . __CLASS__ . '::' . $methodName . '()', E_USER_ERROR);
        }

        $flashName = str_replace('setFlash', '', $methodName);
        $flashName = Inflector::underscore($flashName);

        $flashPath = $this->_getFlashFileName($flashName);
        if ($flashPath === false) {
            trigger_error('Call to undefined method ' . __CLASS__ . '::' . $methodName . '()', E_USER_ERROR);
        }

        $message = isset($arguments[0]) ? $arguments[0] : __d('flash_session', self::FLASH_DIR_NAME . DS . $flashName);
        $element = self::FLASH_DIR_NAME . DS . $flashName;
        $params = isset($arguments[1]) ? $arguments[1] : array();
        $key = isset($arguments[2]) ? $arguments[2] : 'flash';

        CakeSession::write('Message.' . $key, compact('message', 'element', 'params'));
    }

    private function _getFlashFileName($flashName)
    {
        $viewPaths = App::path('View');

        foreach ($viewPaths as $path)
        {
            $flashDirPath = $path . 'Elements' . DS . self::FLASH_DIR_NAME . DS . $flashName. self::EXT;
            if (file_exists($flashDirPath)) {
                return $flashDirPath;
            }
        }

        return false;
    }
}
