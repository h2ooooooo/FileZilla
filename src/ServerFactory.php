<?php

namespace jalsoedesign\FileZilla;

/**
 * Class ServerFactory
 *
 * @package jalsoedesign\FileZilla
 */
class ServerFactory
{
    /**
     * Gets a Server instance from a DOMNode instance
     *
     * @param \DOMNode $domNode
     *
     * @return \jalsoedesign\FileZilla\Server
     */
    public static function fromDomNode(\DOMNode $domNode)
    {
        // Set up a mapper to map XML fields to a properties array
        $tagMapper = [
            'Host'                       => 'host',
            'Port'                       => 'port',
            'Protocol'                   => 'protocol',
            'Type'                       => 'type',
            'User'                       => 'user',
            'Pass'                       => 'password',
            'Keyfile'                    => 'keyFile',
            'Logontype'                  => 'logonType',
            'TimezoneOffset'             => 'timezoneOffset',
            'PasvMode'                   => 'passiveMode',
            'MaximumMultipleConnections' => 'maximumMultipleConnections',
            'EncodingType'               => 'encodingType',
            'BypassProxy'                => 'bypassProxy',
            'Name'                       => 'name',
            'Comments'                   => 'comments',
            'LocalDir'                   => 'localDirectory',
            'RemoteDir'                  => 'remoteDirectory',
            'SyncBrowsing'               => 'synchronizedBrowsing',
            'DirectoryComparison'        => 'directoryComparison',
        ];

        // Set up a caster to use on the properties before being passed to the Server class
        $caster = [
            'port'                       => 'int',
            'protocol'                   => 'int',
            'type'                       => 'int',
            'logonType'                  => 'int',
            'timezoneOffset'             => 'int',
            'maximumMultipleConnections' => 'int',
            'bypassProxy'                => 'bool',
            'synchronizedBrowsing'       => 'bool',
            'directoryComparison'        => 'bool',
        ];

        $properties = [];

        foreach ($domNode->childNodes as $childNode) {
            /** @var \DOMNode $childNode */
            $nodeName = $childNode->nodeName;

            // If we can't find a relevant tag by that name, just skip it
            if (!isset($tagMapper[$nodeName])) {
                continue;
            }

            $property = $tagMapper[$nodeName];

            // Gets the string value of the xml element
            $value = (string)$childNode->textContent;

            // Some attributes, eg. password, are base64 encoded - let's decode it
            if ($encoding = $childNode->attributes->getNamedItem('encoding')) {
                if ($encoding->nodeValue === 'base64') {
                    $value = base64_decode($value);
                }
            }

            // Cast our properties before passing to the class
            if (isset($caster[$property])) {
                settype($value, $caster[$property]);
            }

            $properties[$property] = $value;
        }

        // Return the new server class
        return new Server($properties);
    }
}