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
 * @see https://github.com/basvodde/filezilla/blob/8f874dfe2fdfee4a95cf706bc860252a5793c32e/src/include/server.h
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
}