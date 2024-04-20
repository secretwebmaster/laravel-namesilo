<?php

namespace Secretwebmaster\LaravelNamesilo;

use Exception;

/**
 * ----------------------------------------------------------------------------------------------------
 * 演示功能描述2
 * ----------------------------------------------------------------------------------------------------
 * @link https://github.com/secretwebmaster/laravel-namesilo
 * @since 1.0.0
 * @version 1.0.1
 * 
 * @method 
 * @return 
 * Usage:
 * $namesilo = new Namesilo("Your api key");
 * $namesilo->name_server()->changeNameServers($domain, [
 *      'ns1' => 'xxxxx',
 *      'ns1' => 'xxxxx',
 *      'ns1' => 'xxxxx',
 *      'ns1' => 'xxxxx',
 *      ....
 *      'ns13' => 'xxxxx',
 * ])
 * ----------------------------------------------------------------------------------------------------
 */
class Namesilo
{
    //endpoint
    public $api_url;

    //api key
    public $api_key;

    //debug mode
    public $debug;

    //default query parameters
    public $default_params;

    // //TODO: list all available operations
    // //Or simeply get a list of method from each class?
    // public $avaliable_operations = [
    //     'Domains' => [
    //         Register Domain
    //         Register Domain Drop
    //         Renew Domain
    //         Transfer Domain
    //         List Domains
    //         Get Domain Info
    //         Domain Forward
    //         Domain Forward SubDomain
    //         Domain Forward SubDomain Delete
    //         Add Auto Renewal
    //         Remove Auto Renewal
    //         Domain Lock
    //         Domain Unlock
    //         Check Register Availability
    //         Check Transfer Availability
    //         Whois
    //     ],
    //     'Transfer' => [
    //         Transfer Domain
    //         Check Transfer Status
    //         Transfer Update Change EPP Code
    //         Transfer Update Resend Admin Email
    //         Transfer Update Resubmit To Registry
    //         Retrieve Auth Code
    //     ],
    //     'Contact' => [
    //         Contact List
    //         Contact Add
    //         Contact Update
    //         Contact Delete
    //         Contact Domain Associate
    //     ],
    //     'NameServer' => [
    //         List Registered NameServers
    //         Add Registered NameServer
    //         Modify Registered NameServer
    //         Delete Registered NameServer
    //     ],
    //     'DNS' => [
    //         DNS List Records
    //         DNS Add Record
    //         DNS Update Record
    //         DNS Delete Record
    //         DNS Sec List Records
    //         DNS Sec Add Record
    //         DNS Sec Delete Record
    //     ],
    //     'Portfolio' => [
    //         Portfolio List
    //         Portfolio Add
    //         Portfolio Delete
    //         Portfolio Domain Associate
    //     ],
    //     'Privacy' => [
    //         Add Privacy
    //         Remove Privacy
    //     ],
    //     'Email' => [
    //         List Email Forwards
    //         Configure Email Forward
    //         Delete Email Forward
    //         Registrant Verification Status
    //         Email Verification
    //         Transfer Update Resend Admin Email
    //     ],
    //     'Marketplace' => [
    //         Marketplace Active Sales Overview
    //         Marketplace Add Or Modify Sale
    //         Marketplace Landing Page Update
    //     ],
    //     'Forwarding' => [
    //         Domain Forward
    //         Domain Forward SubDomain
    //         Domain Forward SubDomain Delete
    //         List Email Forwards
    //         Configure Email Forward
    //         Delete Email Forward
    //     ],
    //     'Account' => [
    //         Get Account Balance
    //         Add Account Funds
    //         Get Prices
    //         List Orders
    //         Order Details
    //         List Expiring Domains
    //         Count Expiring Domains
    //     ],
    //     'Auctions' => [
    //         List Auctions
    //         View Auction
    //         View Auctions by IDs
    //         Auction Bid
    //         Auction Buy Now
    //         View Auction History
    //     ]

    // ];

    //Init client
    public function __construct($api_key, $sandbox = false, $debug = false)
    {
        if ($sandbox == true) {
            $this->api_url = "http://sandbox.namesilo.com/api/";
        } else {
            $this->api_url = "https://www.namesilo.com/api/";
        }

        $this->api_key = $api_key;

        $this->default_params = [
            'version' => '1',
            'type' => 'xml',
            'key' => $this->key,
        ];

        $this->debug = $debug;
    }

    // Load methods and pass parameters to corresponding class
    public function __call(string $operation, array $args)
    {
        $class = __NAMESPACE__ . "\\Operations\\" . str()->studly($operation);

        if (class_exists($class)) {
            return new $class($this, $args);
        }else{
            throw new Exception("Class " . $class_name . " is not found. Please make sure Namesilo has this api endpoint");
        }
    }

    // Fetch the api and get xml output string
    private function fetch(string $action, array $params = [], bool $to_array = true)
    {
        $param_arr = array_merge($this->default_params, $params);
        $query = http_build_query($param_arr);
        $url = $this->api_url . $action . '?' . $query;
        $output = file_get_contents($url);
        $result =  $this->parse_xml_str($output, $to_array);

        if ($this->debug) {
            $this->show_debug_message($result);
        }

        if (!$this->is_successful($result)) {
            throw new \Exception($result['reply']['detail']);
        }

        return $result;
    }

    //parse xsml string from output to json string or array
    private function parse_xml_str($output, $to_array = true)
    {
        $output = trim($output);
        $xml = simplexml_load_string($output);
        $json = json_encode($xml);
        if($to_array){
            return json_decode($json, true);
        }else{
            return $json;
        }
    }

    //check if the request is successful
    private function is_successful($result)
    {
        $successful_codes = [300, 301, 302];

        if(empty($result['reply']['code'] || !in_array($result['reply']['code'], $successful_codes))){
            return false;
        }

        return true;
    }

    // Show debug message in lravel.log when debug mode is enabled
    private function show_debug_message($result)
    {
        info($result);
    }











    public function create_contact($fn, $ln, $ad, $cy, $st, $zp, $ct, $em, $ph)
    {
        $result = $this->fetch('contactAdd', [
            ['fn', $fn], // first name
            ['ln', $ln], // last name
            ['ad', $ad], // address
            ['cy', $cy], // city
            ['st', $st], // state
            ['zp', $zp], // zip
            ['ct', $ct], // country
            ['em', $em], // email
            ['ph', $ph], // phone number
        ]);

        if ($this->is_successful($result)) {
            return $result['reply']['contact_id'];
        }

        return false;
    }

    public function update_nameservers($domain, $ns1, $ns2)
    {
        $result = $this->fetch('changeNameServers', [
            ['domain', $domain],
            ['ns1', $ns1],
            ['ns2', $ns2]
        ]);
        if ($this->is_successful($result))
            return true;
        return false;
    }

    public function update_contact_by_domain($domain, $fn, $ln, $ad, $cy, $st, $zp, $ct, $em, $ph)
    {
        $contact_id = $this->get_contact_id_by_domain($domain);
        $result = $this->fetch('contactUpdate', [
            ['contact_id', $contact_id],
            ['fn', $fn], // first name
            ['ln', $ln], // last name
            ['ad', $ad], // address
            ['cy', $cy], // city
            ['st', $st], // state
            ['zp', $zp], // zip
            ['ct', $ct], // country
            ['em', $em], // email
            ['ph', $ph], // phone number
        ]);
        if ($this->is_successful($result))
            return true;
        return false;
    }

    public function delete_contact($contact_id)
    {
        $result = $this->fetch('contactDelete', [
            ['contact_id', $contact_id]
        ]);
        if ($this->is_successful($result))
            return true;
        return false;
    }

    public function register_domain_by_contact_id($domain, $contact_id, $years = 1)
    {
        $result = $this->fetch('registerDomain', [
            ['domain', $domain],
            ['years', $years],
            ['private', 1],
            ['auto_renew', 0],
            ['contact_id', $contact_id],
        ]);
        if (!$this->is_successful($result)) {
            $this->delete_contact($contact_id);
            return false;
        }
        return true;
    }

    public function register_domain($domain, $fn, $ln, $ad, $cy, $st, $zp, $ct, $em, $ph, $years = 1)
    {
        $contact_id = $this->create_contact($fn, $ln, $ad, $cy, $st, $zp, $ct, $em, $ph);
        if (!$contact_id)
            return false;
        $result = $this->fetch('registerDomain', [
            ['domain', $domain],
            ['years', $years],
            ['private', 1],
            ['auto_renew', 0],
            ['contact_id', $contact_id],
        ]);
        if (!$this->is_successful($result)) {
            return false;
        }
        return true;
    }

    public function add_dns_record($domain, $type, $host, $value, $distance = '', $ttl = '')
    {
        $result = $this->fetch('dnsAddRecord', [
            ['domain', $domain],
            ['rrtype', $type],
            ['rrhost', $host],
            ['rrvalue', $value],
            ['rrdistance', $distance],
            ['rrttl', $ttl],
        ]);
        if ($this->is_successful($result))
            return true;
        else
            return false;
    }

    public function delete_dns_record($domain, $record_id)
    {
        $result = $this->fetch('dnsDeleteRecord', [
            ['domain', $domain],
            ['rrid', $record_id],
        ]);
        if ($this->is_successful($result))
            return true;
        else
            return false;
    }

    public function get_dns_records($domain)
    {
        $result = $this->fetch('dnsListRecords', [
            ['domain', $domain],
        ]);
        if ($this->is_successful($result)) {
            if (!isset($result['reply']['resource_record'][0])) {
                $temp_arr = [];
                $temp_arr[0] = $result['reply']['resource_record'];
                return $temp_arr;
            } else {
                return $result['reply']['resource_record'];
            }
        } else {
            return false;
        }
    }

    public function is_domain_available($domain)
    {
        $result = $this->fetch('checkRegisterAvailability', [['domains', $domain]]);
        if ($this->is_successful($result) && isset($result['reply']['available']))
            return 'available';
        if ($this->is_successful($result) && isset($result['reply']['invalid']))
            return 'invalid';
        if ($this->is_successful($result) && isset($result['reply']['unavailable']))
            return 'unavailable';
        return false;
    }

    public function send_auth_code($domain)
    {
        return $this->is_successful($this->fetch('retrieveAuthCode', [['domain', $domain]]));
    }

    public function get_contact_by_id($contact_id)
    {
        $result = $this->fetch('contactList', [['contact_id', $contact_id]]);
        if (!$this->is_successful($result))
            return false;
        return $result['reply']['contact'];
    }

    public function get_all_contacts()
    {
        $result = $this->fetch('contactList');
        if (!$this->is_successful($result))
            return false;
        return $result['reply']['contact'];
    }

    public function get_contact_by_domain($domain)
    {
        $contact_id = $this->get_contact_id_by_domain($domain);
        if (!$contact_id)
            return false;
        return $this->get_contact_by_id($contact_id);
    }

    public function list_domains()
    {
        $result = $this->fetch('listDomains');
        if (!$this->is_successful($result))
            return false;
        return $result['reply']['domains']['domain'];
    }

    public function get_nameservers($domain)
    {
        $domain_info = $this->get_domain_info($domain);
        if (!$domain_info)
            return false;
        if (is_array($domain_info['nameservers']))
            return $domain_info['nameservers']['nameserver'];
        else
            return false;
    }

    public function privacy_status($domain)
    {
        $domain_info = $this->get_domain_info($domain);
        if (!$domain_info)
            return false;
        $private = strtolower($domain_info['private']);
        if ($private == 'yes')
            return true;
        else
            return false;
    }

    public function add_privacy($domain)
    {
        $result = $this->fetch('addPrivacy', [['domain', $domain]]);
        return $this->is_successful($result);
    }

    public function remove_privacy($domain)
    {
        $result = $this->fetch('removePrivacy', [['domain', $domain]]);
        return $this->is_successful($result);
    }

    public function lock_status($domain)
    {
        $domain_info = $this->get_domain_info($domain);
        if (!$domain_info)
            return false;
        $private = strtolower($domain_info['locked']);
        if ($private == 'yes')
            return true;
        else
            return false;
    }

    public function domain_lock($domain)
    {
        $result = $this->fetch('domainLock', [['domain', $domain]]);
        return $this->is_successful($result);
    }

    public function domain_unlock($domain)
    {
        $result = $this->fetch('domainUnlock', [['domain', $domain]]);
        return $this->is_successful($result);
    }

    public function get_contact_id_by_domain($domain)
    {
        $domain_info = $this->get_domain_info($domain);
        if (!$domain_info)
            return false;
        $contact_id = $domain_info['contact_ids']['registrant'];
        return $contact_id;
    }

    public function get_domain_info($domain)
    {
        $result = $this->fetch('getDomainInfo', [['domain', $domain]]);
        if ($this->is_successful($result))
            return $result['reply'];
        else
            return false;
    }

    public function get_account_balance()
    {
        $result = $this->fetch('getAccountBalance');
        if ($this->is_successful($result))
            return $result['reply']['balance'];
        else
            return false;
    }
}
