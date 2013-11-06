<?php
$root = C('ROOT');
require_once($root.'/Common/search/sphinxapi.php');

class XTSearch {
    protected $client;

    // Constructor
    public function __construct($host = 'localhost', $port = 9312) {
        $this->client = new SphinxClient();
        $this->client->SetServer($host, $port);
        $this->init();
    }

    /**
     * Init config.
     */
    public function init() {
        #$this->client->SetSortMode(SPH_SORT_EXTENDED, "@relevance DESC, ctime DESC, pub_id DESC");
        $this->client->SetArrayResult(true);
        # pass
    }

    /**
     * Sphinx query wrapper.
     */
    public function query($query_string, $index_family = '*') {
        return $this->client->Query($query_string, $index_family);
    }

    /**
     * Format the Sphinx result into ThinkPHP model->select return format.
     * @fail return false.
     * @success return ThinkPHP format array.
     */
    public function tp_parse_result($result) {
        if (!$result) 
            return false;

        $ans = array();
        foreach ($result['matches'] as $attr) {
            array_push($attr['attrs'], $ans);
        }

        return $ans;
    }
}
?>
