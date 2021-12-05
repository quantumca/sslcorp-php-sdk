<?php

namespace SslCorp;

use ArrayObject;

class Order extends BaseApi
{
    /**
     * Place a new Order
     *
     * @param array $data
     * int `product` The product code of the ssl certificate being purchased. Select only one code from the following choices: 100 (for EV UCC SSL) / 101 (for UCC SSL) / 103 (for High Assurance SSL) / 102 (for EV SSL) / 104 (for Free SSL) / 105 (for Wildcard SSL) / 106 (for Basic SSL) / 107 (for Premium SSL)
     * int `period` The number of days the certificate is valid for. Depending on what is specified by the product key, different options are available: 365 or 730 for EV SSL certs30 or 90 for Free Trial SSL certs365, 730, 1095, 1461, or 1826 for all others
     * string|null `unique_value` An alphanumeric string that ensures the uniqueness of the request. If you do not supply a unique value, one will be generated for you (SSL.com uses a random 10-digit hexadecimal number for this purpose). Specifying a unique value in this way is useful if you want to generate DCV files or CNAME entries outside of SSL.com’s user portal. Must be unique to each CSR and domains combination. Ignored if no CSR is in the request.
     * string `csr`
     * string `server_software` 1 OTHER2 AOL3 Apache-ModSSL4 Apache-SSL (Ben-SSL, not Stronghold)5 C2Net Stronghold6 Cisco 3000 Series VPN Concentrator7 Citrix8 Cobalt Raq9 Covalent Server Software10 Ensim11 HSphere12 IBM HTTP Server13 IBM Internet Connection Server14 iPlanet15 Java Web Server (Javasoft / Sun)16 Lotus Domino17 Lotus Domino Go!18 Microsoft IIS 1.x to 4.x19 Microsoft IIS 5.x to 6.x20 Microsoft IIS 7.x and later21 Netscape Enterprise Server22 Netscape FastTrack23 Novell Web Server24 Oracle25 Plesk26 Quid Pro Quo27 R3 SSL Server28 Raven SSL29 RedHat Linux30 SAP Web Application Server31 Tomcat32 Website Professional33 WebStar 4.x and later34 WebTen (from Tenon)35 WHM/CPanel36 Zeus Web Server37 Nginx38 Heroku39 Amazon Load Balancer
     * array `domains`  {"domains" : {"www.mysite.com" : {"dcv" : "admin@mysite.com"}}, "mail.domain.io" : {"dcv : "HTTP_CSR_HASH"}}}
     * string `organization`
     * string `organization_unit`
     * string `post_office_box`
     * string `street_address_1`
     * string `street_address_2`
     * string `street_address_3`
     * string `locality`
     * string `state_or_province`
     * string `postal_code`
     * string `country`
     * string `duns_number`
     * string `company_number` Company registration number
     * array `joi` {"joi" : {"locality": "Houston", "state_or_province" : "Texas", "country" : "US", "incorporation_date" : "2002-06-01"}, "assumed_name" : "SSL.com", "business_category" : "b"}
     * int `ca_certificate_id` If specified, this overrides SSL.com’s default choice of CA certificate/key to be used to issue this certificate. This functionality is only available by special agreement with SSL.com.
     * string `external_order_number` This identifier is provided for integration with partner systems. If the external system has a record or identifier that needs to associate with this particular ssl certificate order, then the developer provides an external order number or identifier so that the developer can make the association.
     * string `hide_certificate_reference` y/n y hide the certificate reference number in the emailed ssl certificaten default; show the certificate reference number in the emailed ssl certificate
     * array `callback` This is the callback SSL.com will make once the certificate is issued and ready for collection. The url is "called" via the method (post or get).  Example: {callback":{"url":"https://www.domain.com/receive_certificate.asp","method":"post",auth":{"basic":{"username":"your_username","password":"your_password"}},"parameters":{"certificate_hook":"cert","custom_1":"any_value","custom_2":"any_value","etc":"etc"}}.
     * array `contacts` Required only if csr is specified, otherwise contacts will be ignored. Contacts with administrative, billing, technical, validation or all roles. Specify one for each (total of 4): [administrative, billing, technical, validation]. You can also specify one with role all which will used as the default contact in place of one or more of the previous contact roles if they are not specified.  Example: "contacts":{"all":{"first_name":"Joe","last_name":"Bob","email":"jbob@domain.com","phone":"+123456789","country":"US"}}.
     * array `app_rep` Applicant Representative used for callback. Only for OV certificates. All values are optional. Example: {"app_rep" : {"first_name" : "Joe", "last_name" : "Bob", "email_address" : "bob@mysite.com", "phone_number" : "111-111-1111", "title" : "owner", "country" : "US", "callback_method" : "t"}
     * array `payment_method` Optional payment method. If payment method is specified, then payment will override the default method of deducting funds from the prepaid deposit/funded account associated with the `account_key`. Example: {"credit_card":{"first_name":"Bob","last_name":"Smith","number":"370000000000002","expires":"0119","cvv":"007,"postal_code":"77098","country":"US"}}
     *
     * @return \SslCorp\Interfaces\CreateCertificateRes
     */
    public function createCertificate($data)
    {
        return $this->post('/certificates', collect($data)->filter()->toArray());
    }

    /**
     * Update certificate
     *
     * @param string $ref String ref is the certificate reference number (or voucher code) of the SSL.com certificate order. Example: co-abcd1234.
     * @param array $data
     */
    public function updateCertifiate($ref, $data)
    {
        return $this->put('/certificate/' . $ref, collect($data)->filter()->toArray());
    }

    /**
     * Change domains or DCV
     *
     * @param string $ref String ref is the certificate reference number (or voucher code) of the SSL.com certificate order. Example: co-abcd1234.
     * @param array $domains  {"domains" : {"www.mysite.com" : {"dcv" : "admin@mysite.com"}}, "mail.domain.io" : {"dcv : "HTTP_CSR_HASH"}}}
     */
    public function changeDomainsOrDcv($ref, $domains)
    {
        return $this->put('/certificate/' . $ref, [
            'domains' => $domains,
        ]);
    }

    /**
     * Retry DCV
     *
     * @param string $ref String ref is the certificate reference number (or voucher code) of the SSL.com certificate order. Example: co-abcd1234.
     */
    public function retryDcv($ref)
    {
        return $this->post('/certificate/' . $ref . '/retry_domain_validation', []);
    }

    /**
     * Retry DCV
     *
     * @param string $ref String ref is the certificate reference number (or voucher code) of the SSL.com certificate order. Example: co-abcd1234.
     */
    public function replaceDomain($ref, $domain_dcv, $domains)
    {
        return $this->post('/certificate/' . $ref . '/replace', ['domains' => $domain_dcv, 'cert_names' => $domains,]);
    }

    /**
     * List all Certificates
     *
     * @param string $per_page The number of records per page (default is 10 if unspecified).
     * @param string $page The page number. Example: if per_page is set to 10, and page is 5, then records 51-60 will be returned.
     * @param string $start Beginning of date range when the certificate orders were created. Example: 01-31-2012.
     * @param string $end Ending of date range when the certificate orders were created. If not specified then defaults to now. Example: 04-30-2015.
     * @param string $filter Filter the result set. Currently vouchers is supported and only returns unused available certificate order credits. If this parameter is not specified all certificate orders are returned. Example: vouchers.
     * @param string $search Advanced search by criteria such as product, expiration, certificate fields, etc.
     * @param string $fields Return only the requested fields.
     */
    public function listCertificates($per_page, $page, $start, $end, $filter, $search, $fields)
    {
        return $this->get('/certificates', [
            'per_page' => $per_page,
            'page' => $page,
            'start' => $start,
            'end' => $end,
            'filter' => $filter,
            'search' => $search,
            'fields' => $fields,
        ]);
    }

    /**
     * Retrieve a Certificate
     *
     * @param string $ref String ref is the certificate reference number (or voucher code) of the SSL.com certificate order. Example: co-abcd1234.
     * @param string $response_type zip|netscape|pkcs7|individually Specifies the type/format of the certificates that are being returned, if applicable. Default: individually
     * @param string $response_encoding base64|base64binaryExample
     */
    public function downloadCertificate($ref, $response_type, $response_encoding)
    {
        return $this->get('/certificate/' . $ref, [
            'response_type' => $response_type,
            'response_encoding' => $response_encoding,
        ]);
    }

    /**
     * Revoke certificates in an order
     * @param string $ref String ref is the certificate reference number (or voucher code) of the SSL.com certificate order. Example: co-abcd1234.
     * @param string $reason
     * @param string[] $serials
     */
    public function revokeCertificate($ref, $reason, $serials)
    {
        return $this->delete('/certificate/' . $ref, collect([
            'reason' => $reason,
            'serials' => $serials,
        ])->filter()->toArray());
    }
}
