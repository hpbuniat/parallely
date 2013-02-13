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
namespace parallely\Transport;


/**
 * Parallel-Transport for File
 *
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011-2013 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 * @link https://github.com/hpbuniat/parallely
 */
class File extends \parallely\AbstractTransport implements \parallely\TransportInterface {

    /**
     * The file-directory
     *
     * @var string
     */
    protected $_sDirectory;

    /**
     * Memcache-Options
     *
     * @var array
     */
    protected $_aOptions = array(
        'path',
    );

    /**
     * (non-PHPdoc)
     * @see \parallely\AbstractTransport::_prepare()
     */
    protected function _prepare() {
        if (empty($this->_sDirectory) === true) {
            $sUniqueId = uniqid(self::PREFIX);
            $this->_sDirectory = $this->_aOptions['path'] . DIRECTORY_SEPARATOR . $sUniqueId;
            if (is_dir($this->_sDirectory) !== true) {
                mkdir($this->_sDirectory, 0744, true);
            }

            if (is_dir($this->_sDirectory) !== true) {
                throw new \parallely\Exception(\parallely\Exception::SETUP_ERROR);
            }

        }

        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \parallely\TransportInterface::read()
     */
    public function read($sId) {
        $mReturn = false;
        $this->_prepare();

        $sFile = $this->_sDirectory . DIRECTORY_SEPARATOR . self::PREFIX . $sId;
        if (file_exists($sFile) === true) {
            $mReturn = file_get_contents($sFile);
        }

        return $mReturn;
    }

    /**
     * (non-PHPdoc)
     * @see \parallely\TransportInterface::write()
     */
    public function write($sId, $mData) {
        $this->_prepare();

        $sFile = $this->_sDirectory . DIRECTORY_SEPARATOR . self::PREFIX . $sId;
        file_put_contents($sFile, $mData);

        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \parallely\TransportInterface::free()
     */
    public function free() {
        $this->_prepare();

        $oIter = new \DirectoryIterator($this->_sDirectory);
        foreach ($oIter as $oFile) {

            /* @var $oFile \DirectoryIterator */
            if ($oFile->isFile() === true) {
                unlink($oFile->getPathname());
            }
        }

        rmdir($this->_sDirectory);

        return $this;
    }
}
