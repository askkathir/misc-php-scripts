<?php
	// File with URLS to redirect. One URL per line
	define('FILE_NAME_URLS', 'urls.txt');

	// Folder where the structure of redirects will be generated
	define('GENERATED_URL_PATH', 'generated-urls');

	// Domain name you wish to redirect
	define('OLD_DOMAIN_NAME', 'mogosselin.com');

	// The domain name where the old one will be redirected to
	define('NEW_DOMAIN_NAME', 'learnphp.io');

	function echoStatus($message) {
		echo $message . "...\n\r";
	}

	function error($message) {
		echo "\n\r==============================================\n\r";
		echo "ERROR : " . $message;
		echo "\n\r==============================================\n\r";
		die("script aborted...");
	}

	function removeReturns($str) {
		$str = str_replace(PHP_EOL, '', $str);
		return $str;
	}

	function validateState() {
		validateUrlFile();
		//validateGeneratedUrlsDirectory();
	}

	function validateUrlFile() {
		if (!file_exists(FILE_NAME_URLS))
			error("File " . FILE_NAME_URLS . " not found.");		
	}

	function validateGeneratedUrlsDirectory() {
		if (file_exists(GENERATED_URL_PATH)) {
			error("Folder " . GENERATED_URL_PATH . " already exists. Please delete it or change its name.");
		} else {
			if (!mkdir(GENERATED_URL_PATH, 0777, true)) {
    			error("Failed to create folder " . GENERATED_URL_PATH);
			}
		}
	}

	function getUrls() {
		$urls = file(FILE_NAME_URLS);
		return $urls;
	}

	function generateRedirect($urls) {
		foreach ($urls as $url) {
			$newUrl = str_replace(OLD_DOMAIN_NAME, NEW_DOMAIN_NAME, $url);
			$newUrl = str_replace(PHP_EOL, '', $newUrl);

			if ($newUrl == $url)
				error("Tried to make a redirection from " . OLD_DOMAIN_NAME . " to " . NEW_DOMAIN_NAME . " but both the old and new URL are the same. Please check that the constants are set correctly and that your URLs in your file " . FILE_NAME_URLS . " contains the old domain name.");

			$createdFolder = generateDirectoryStructure($url);

			echoStatus("creating php file for redirection for " . $newUrl);
			generatePhpRedirect($createdFolder, $newUrl);
		}
	}

	function generateDirectoryStructure($url) {

		// changes the old domain in $url to new domain value
		$folderToCreate = substr($url, strpos($url, OLD_DOMAIN_NAME) + strlen(OLD_DOMAIN_NAME) );
		
		$folderToCreate = "./" . GENERATED_URL_PATH . $folderToCreate;

		$folderToCreate = trim(removeReturns($folderToCreate));

		if (!mkdir($folderToCreate, 0777, true)) {
			error("Failed to create folder " . GENERATED_URL_PATH);
		}

		return $folderToCreate;
	}

	function generatePhpRedirect($path, $url) {
		$pathToFile = $path;

		$pathToFile = $pathToFile . "index.php";

		echo "path to file: " . $pathToFile;

		$phpRedirectFile = fopen($pathToFile, "w") or error("Unable to create file for writing " . $pathToFile);
		
		$txt = <<<EOD
<?php 
header("HTTP/1.1 301 Moved Permanently"); 
header("Location: $url"); 
die();
?>
EOD;

		fwrite($phpRedirectFile, $txt);
		fclose($phpRedirectFile);		
	}

	echoStatus("initializing and checking status");
	validateState();

	echoStatus("getting urls from " . FILE_NAME_URLS );
	$urls = getUrls();
	
	echoStatus("read " . sizeof($urls) . " urls from " . FILE_NAME_URLS);

	echoStatus("generating urls...");
	generateRedirect($urls);

?>