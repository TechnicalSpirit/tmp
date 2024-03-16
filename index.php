<?php

namespace NamePlugin;

class NameApi {

    private string $api_url;

    public function __construct(string $api_url) {
        $this->api_url = $api_url;
    }

    public function list_vacansies( object $post, int $vid = 0): mixed {
        global $wpdb;

        $this->result = [];
        $this->page = 0;

        while(true)
        {
            $result_call = $this->make_request();

            if ($this->is_something_go_wrong($result_call)) {
                return false;
            } 
            
            if ($this->is_need_find_user_by_id($vid)){
                return $this->get_user_by_id($vid);
            }

            if($this->is_next_page_exist()){
                $this->page++;
            }
            else{
                return $this->result;
            }

            $this->add_to_result($result_call);
        }
    }

    private function get_user_by_id(int $vid):mixed
    {
        foreach ($result_call->objects as $value) {
            if ($value->id == $vid) {
                return $value;
            }
        }
        return false;
    }

    private function add_to_result(object $result_call):void
    {
        array_merge($result_call->objects, $this->result);
    }

    private function is_need_find_user_by_id($vid): bool
    {
        return $vid > 0;
    }

    private function make_request(): mixed
    {
        $id_user = $this->self_get_option('superjob_user_id');

        $params = "status=all&id_user=$id_user&with_new_response=0&order_field=date&order_direction=desc&page={$this->page}&count=100";
        $url = "$this->api_url/hr/vacancies/?$params";

        $result = $this->api_send($url);

        return json_decode($result);
    }

    private function is_next_page_exist():bool
    {
        return ( bool ) $result_object->more;
    }

    private function is_something_go_wrong(): bool
    {
        return ! ($res !== false && is_object($result) && isset($result->objects) );
    }

    public function api_send() 
    {
        return '';
    }

    public function self_get_option($option_name) 
    {
        return '';
    }
}
