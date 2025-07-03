<?php


class Page {

	private $body_list;

	function __construct() {
		$this->body_list = [];
	}

	function body_add( callable $function ): void {
		$this->body_list[] = $function;
	}

	function print(): void {
		echo '<!doctype html>' . "\n";
		echo '<html>' . "\n";
		echo '<head>' . "\n";
		echo '<meta charset="utf-8">' . "\n";
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
		echo '<title>Ατομικά Πρωταθλήματα</title>' . "\n";
		echo '<link rel="shortcut icon" href="strategy.png">' . "\n";
		echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">' . "\n";
		echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">' . "\n";
		echo '</head>' . "\n";
		echo '<body class="container d-flex flex-column p-2">' . "\n";
		echo '<h1 class="m-2">Ατομικά Πρωταθλήματα</h1>' . "\n";
		foreach ( $this->body_list as $body ) {
			$body();
		}
		echo '<script src="color-scheme.js"></script>' . "\n";
		echo '</body>' . "\n";
		echo '</html>' . "\n";
	}

	static function credits(): void {
		echo '<hr class="m-2">' . "\n";
		echo '<div class="d-flex flex-row flex-wrap">' . "\n";
		echo '<a class="m-2 btn btn-outline-secondary btn-sm" href="https://github.com/constracti/individual-championships-server" target="_blank">' . "\n";
		echo '<span class="bi bi-github"></span>' . "\n";
		echo '<span>@constracti</span>' . "\n";
		echo '</a>' . "\n";
		echo '<a class="m-2 btn btn-outline-secondary btn-sm" href="https://getbootstrap.com/" target="_blank">' . "\n";
		echo '<span class="bi bi-bootstrap"></span>' . "\n";
		echo '<span>Bootstrap</span>' . "\n";
		echo '</a>' . "\n";
		echo '<a class="m-2 btn btn-outline-secondary btn-sm" href="https://www.flaticon.com/" target="_blank">' . "\n";
		echo '<span class="bi bi-box-arrow-up-right"></span>' . "\n";
		echo '<span>Flaticon</span>' . "\n";
		echo '</a>' . "\n";
		echo '</div>' . "\n";
	}
}
