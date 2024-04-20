<?php

namespace Secretwebmaster\LaravelNamesilo\Operations;

class Namesilo extends AbstractOperation
{
    /**
     * ----------------------------------------------------------------------------------------------------
     * Change nameservers of a domain
     * ----------------------------------------------------------------------------------------------------
     * @link https://www.namesilo.com/api-reference#nameserver/change-nameserver
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string $domain - A comma-delimited list of up to 200 domains
     * @param string $ns1 - Nameserver 1
     * @param string $ns2 - Nameserver 2
     * @param string $ns3 - Nameserver 3
     * @param string $ns4 - Nameserver 4
     * @param string $ns5 - Nameserver 5
     * @param string $ns6 - Nameserver 6
     * @param string $ns7 - Nameserver 7 
     * @param string $ns8 - Nameserver 8
     * @param string $ns9 - Nameserver 9
     * @param string $ns10 - Nameserver 10
     * @param string $ns11 - Nameserver 11
     * @param string $ns12 - Nameserver 12
     * @param string $ns13 - Nameserver 13
     * ----------------------------------------------------------------------------------------------------
     */
    public function change(string $domain, string $ns1, string $ns2, string $ns3 = null, string $ns4 = null, string $ns5 = null, string $ns6 = null, string $ns7 = null, string $ns8 = null, string $ns9 = null, string $ns10 = null, string $ns11 = null, string $ns12 = null, string $ns13 = null)
    {
        $options = 'changeNameServers';
        $params = [
            'domain' => $domain,
            'ns1' => $ns1,
            'ns2' => $ns2,
            'ns3' => $ns3,
            'ns4' => $ns4,
            'ns5' => $ns5,
            'ns6' => $ns6,
            'ns7' => $ns7,
            'ns8' => $ns8,
            'ns9' => $ns9,
            'ns10' => $ns10,
            'ns11' => $ns11,
            'ns12' => $ns12,
            'ns13' => $ns13,
        ];

        return $this->client->fetch($options, $params);
    }

    public function list(string $domain)
    {
        $options = 'changeNameServers';
        $params = [];
        return $this->client->fetch($options, $params);
    }

    public function add(string $domain)
    {
        $options = 'changeNameServers';
        $params = [];
        return $this->client->fetch($options, $params);
    }

    public function modify(string $domain)
    {
        $options = 'changeNameServers';
        $params = [];
        return $this->client->fetch($options, $params);
    }

    public function delete(string $domain)
    {
        $options = 'changeNameServers';
        $params = [];
        return $this->client->fetch($options, $params);
    }
}
