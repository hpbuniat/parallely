<?php
/**
 * parallely
 *
 * Copyright (c) 2011-2013, Hans-Peter Buniat <hpbuniat@googlemail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * * Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in
 * the documentation and/or other materials provided with the
 * distribution.
 *
 * * Neither the name of Hans-Peter Buniat nor the names of his
 * contributors may be used to endorse or promote products derived
 * from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package parallely
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011-2013 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace parallely;


/**
 * Builder for Transports
 *
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011-2013 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 * @link https://github.com/hpbuniat/parallely
 */
class Builder {

    /**
     * Build the executor
     *
     * @param  array $aStack
     * @param  mixed $mAdapter
     * @param  mixed $mConfig
     *
     * @return Execute
     */
    public static function build(array $aStack, $mAdapter, $mConfig = null) {
        try {
            $oTransport = self::transport($mAdapter, $mConfig);
        }
        catch (Exception $oBuildException) {
            $oTransport = null;
        }

        return new Execute($aStack, $oTransport);
    }

    /**
     * Build a transport
     *
     * @param  mixed $mAdapter
     * @param  mixed $mConfig
     *
     * @return TransportInterface
     *
     * @throws Exception
     */
    public static function transport($mAdapter, $mConfig = null) {

        $sClass = $mAdapter;
        if (is_string($mAdapter) === true) {
            $bPrefix = false;
            if (class_exists($mAdapter) !== true) {
                $bPrefix = true;
            }
            else {
                $oReflection = new \ReflectionClass($sClass);
                if ($oReflection->implementsInterface('\\parallely\\TransportInterface') !== true) {
                    $bPrefix = true;
                }

                unset($oReflection);
            }

            if ($bPrefix === true) {
                $sClass = sprintf('\\parallely\\Transport\\%s', ucfirst(strtolower($mAdapter)));
            }
        }

        $oTransport = null;
        if (is_string($mAdapter) === true and class_exists($sClass) === true) {
            $oReflection = new \ReflectionClass($sClass);
            if ($oReflection->implementsInterface('\\parallely\\TransportInterface') === true) {
                $oTransport = new $sClass();

                /* @var $oTransport TransportInterface */
                if ($oReflection->hasMethod('setConfig') === true) {
                    $oTransport->setConfig(self::_buildConfig($mConfig));
                }
            }
        }
        else {
            $oTransport = $mAdapter;
        }

        if (($oTransport instanceof TransportInterface) !== true) {
            //throw new Exception(Exception::UNKNOWN_TRANSPORT);
            $oTransport = null;
        }

        return $oTransport;
    }

    /**
     * Create the parallely-config
     *
     * @param  mixed $mConfig
     *
     * @return \stdClass
     */
    protected static function _buildConfig($mConfig) {
        $oConfig = new \stdClass();
        if (is_array($mConfig) === true) {
            foreach($mConfig as $sKey => $mValue) {
                $oConfig->$sKey = $mValue;
            }
        }
        elseif ($mConfig instanceof \stdClass) {
            $oConfig = $mConfig;
        }

        return $oConfig;
    }
}
