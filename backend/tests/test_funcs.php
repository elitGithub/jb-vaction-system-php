<?php

function get_segments($ignore_custom_routes=NULL) {

    //figure out how many segments needs to be ditched
    $psuedo_url = str_replace('://', '', BASE_URL);
    $psuedo_url = rtrim($psuedo_url, '/');
    $bits = explode('/', $psuedo_url);
    $num_bits = count($bits);

    if ($num_bits>1) {
        $num_segments_to_ditch = $num_bits-1;
    } else {
        $num_segments_to_ditch = 0;
    }

    $assumed_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  $_SERVER['REQUEST_URI'];

    if (!isset($ignore_custom_routes)) {
        $assumed_url = attempt_add_custom_routes($assumed_url);
    }

    $data['assumed_url'] = $assumed_url;

    $assumed_url = str_replace('://', '', $assumed_url);
    $assumed_url = rtrim($assumed_url, '/');

    $segments = explode('/', $assumed_url);

    for ($i=0; $i < $num_segments_to_ditch; $i++) {
        unset($segments[$i]);
    }

    $data['segments'] = array_values($segments);
    return $data;
}

function attempt_add_custom_routes($target_url) {
    //takes a nice URL and returns the assumed_url
    $target_url = rtrim($target_url, '/');
    $target_segments_str = str_replace(BASE_URL,'', $target_url);
    $target_segments = explode('/',$target_segments_str);

    foreach (CUSTOM_ROUTES as $custom_route => $custom_route_destination) {
        $custom_route_segments = explode('/',$custom_route);
        if(count($target_segments) == count($custom_route_segments)){
            if ($custom_route == $target_segments_str) { //perfect match; return immediatly
                $target_url = str_replace($custom_route, $custom_route_destination, $target_url);
                break;
            }
            $abort_route_check = false;
            $correction_counter = 0;
            $new_custom_url = rtrim(BASE_URL.$custom_route_destination,'/');
            for ($i=0; $i < count($target_segments); $i++) {
                if($custom_route_segments[$i] == $target_segments[$i]){
                }else if($custom_route_segments[$i] == "(:num)" && is_numeric($target_segments[$i]) ){
                    $correction_counter++;
                    $new_custom_url = str_replace('$'.$correction_counter, $target_segments[$i], $new_custom_url);
                }else if($custom_route_segments[$i] == "(:any)"){
                    $correction_counter++;
                    $new_custom_url = str_replace('$'.$correction_counter, $target_segments[$i], $new_custom_url);
                }else{
                    $abort_route_check = true;
                    break;
                }
            }
            if(!$abort_route_check){
                $target_url = $new_custom_url;
            }
        }
    }
    return $target_url;
}

function attempt_return_nice_url($target_url) {
    //takes an assumed_url and returns the nice_url

    foreach (CUSTOM_ROUTES as $key => $value) {

        $pos = strpos($target_url, $value);

        if (is_numeric($pos)) {
            $target_url = str_replace($value, $key, $target_url);
        }

    }

    return $target_url;
}

$data = get_segments();

function segment ($num, $var_type = null)
{
    $segments = SEGMENTS;
    if (isset($segments[$num])) {
        $value = $segments[$num];
    } else {
        $value = '';
    }

    if (isset($var_type)) {
        settype($value, $var_type);
    }

    return $value;
}

function previous_url ()
{
    if (isset($_SERVER['HTTP_REFERER'])) {
        $url = $_SERVER['HTTP_REFERER'];
    } else {
        $url = '';
    }
    return $url;
}

function current_url ()
{
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $current_url;
}

function redirect ($target_url)
{
    $str = substr($target_url, 0, 4);
    if ($str != 'http') {
        $target_url = BASE_URL . $target_url;
    }

    header('location: ' . $target_url);
    die();
}

function anchor ($target_url, $text, $attributes = null, $additional_code = null)
{
    $str = substr($target_url, 0, 4);
    if ($str != 'http') {
        $target_url = BASE_URL . $target_url;
    }

    $target_url = attempt_return_nice_url($target_url);

    $text_type = gettype($text);

    if ($text_type == 'boolean') {
        return $target_url;
    }

    $extra = '';
    if (isset($attributes)) {
        foreach ($attributes as $key => $value) {
            $extra .= ' ' . $key . '="' . $value . '"';
        }
    }

    if (isset($additional_code)) {
        $extra .= ' ' . $additional_code;
    }

    $link = '<a href="' . $target_url . '"' . $extra . '>' . $text . '</a>';
    return $link;
}

function url_title ($string)
{
    $string = trim($string);
    $string = preg_replace('/\s+/', ' ', $string);
    $string = preg_replace("/[^A-Za-z0-9 _]/", '', $string);
    $string = rawurlencode(utf8_encode($string));
    $string = preg_replace('/-+/', '-', $string);
    $string = str_replace("%20", '-', $string);
    return $string;
}

function api_auth ()
{
    //find out where the api.json file lives
    $validation_complete = false;
    $target_url = str_replace(BASE_URL, '', current_url());
    $segments = explode('/', $target_url);

    if ((isset($segments[0])) && (isset($segments[1]))) {
        $current_module_bits = explode('-', $segments[0]);
        $current_module = $current_module_bits[0];
        $filepath = APPPATH . 'modules/' . $current_module . '/assets/api.json';

        if (file_exists($filepath)) {
            //extract the rules for the current path
            $target_method = $segments[1];
            $settings = file_get_contents($filepath);
            $endpoints = json_decode($settings, true);

            $current_uri_path = str_replace(BASE_URL, '', current_url());
            $current_uri_bits = explode('/', $current_uri_path);

            foreach ($endpoints as $rule_name => $api_rule_value) {
                $segments_match = true;

                if (isset($api_rule_value['url_segments'])) {
                    //ignore placeholders for decent comparison
                    $target_url_segments = $api_rule_value['url_segments'];
                    $bits = explode('/', $target_url_segments);
                    $required_bits = [];

                    foreach ($bits as $key => $value) {
                        if (!is_numeric(strpos($value, '{'))) {
                            $required_segments[$key] = $value;
                        }
                    }

                    foreach ($current_uri_bits as $key => $value) {
                        if (isset($required_segments[$key])) {
                            if ($value !== $required_segments[$key]) {
                                $segments_match = false;
                            }
                        }
                    }

                    if ($segments_match == true) {
                        $token_validation_data['endpoint'] = $rule_name;
                        $token_validation_data['module_name'] = $current_module;
                        $token_validation_data['module_endpoints'] = $endpoints;

                        $api_class_location = APPPATH . 'engine/Api.php';

                        if (file_exists($api_class_location)) {
                            include_once $api_class_location;
                            $api_helper = new Api;
                            $api_helper->_validate_token($token_validation_data);
                            $validation_complete = true;
                        }
                    }

                    if (isset($required_segments)) {
                        unset($required_segments);
                    }
                }
            }
        }
    }

    if ($validation_complete == false) {
        http_response_code(401);
        echo "Invalid token.";
        die();
    }
}

function make_rand_str ($strlen, $uppercase = false)
{
    $characters = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    $random_string = '';
    for ($i = 0; $i < $strlen; $i++) {
        $random_string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    if ($uppercase == true) {
        $random_string = strtoupper($random_string);
    }
    return $random_string;
}

function json ($data, $kill_script = null)
{
    echo '<pre>' . json_encode($data, JSON_PRETTY_PRINT) . '</pre>';

    if (isset($kill_script)) {
        die();
    }
}