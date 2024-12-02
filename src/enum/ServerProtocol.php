<?php

namespace jalsoedesign\filezilla\enum;

/**
 * Class ServerProtocol
 *
 * @package jalsoedesign\filezilla\enum
 *
 * @see https://svn.filezilla-project.org/svn/FileZilla3/trunk/src/include/server.h - enum ServerProtocol
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

	const INSECURE_WEBDAV = 19;

	const RACKSPACE = 20;

	const STORJ_GRANT = 21;

	const S3_SSO = 22;

	const GOOGLE_CLOUD_SVC_ACC = 23;

	const CLOUDFLARE_R2 = 24;

	const MAX_VALUE = 24; // CLOUDFLARE_R2
}
