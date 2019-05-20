<?php
/**
 * Created by PhpStorm.
 * User: aj
 * Date: 07-06-2017
 * Time: 12:42
 */

namespace jalsoedesign\FileZilla\enum;

/**
 * Class ServerProtocol
 *
 * @package jalsoedesign\FileZilla\enum
 *
 * @see https://svn.filezilla-project.org/svn/FileZilla3/trunk/src/include/server.h
 */
class ServerProtocol extends Enum {
	const UNKNOWN = -1;
	const FTP = 0; // FTP - File Transfer Protocol with optional encryption
	const SFTP = 1; // SFTP - SSH File Transfer Protocol
	const HTTP = 2; // HTTP - Hypertext Transfer Protocol
	const FTPS = 3; // FTP over implicit TLS
	const FTPES = 4; //  FTP over explicit TLS
	const HTTPS = 5; // HTTP over TLS
	const INSECURE_FTP = 6; // Insecure File Transfer Protocol

    const S3 = 7; // Amazon Simple Storage Service

    const STORJ = 8;
    const WEBDAV = 9;

    const AZURE_FILE = 10;
    const AZURE_BLOB = 11;

    const SWIFT = 12;

    const GOOGLE_CLOUD = 13;
    const GOOGLE_DRIVE = 14;

    const DROPBOX = 15;

    const ONEDRIVE = 16;

    const B2 = 17;

    const BOX = 18;
}