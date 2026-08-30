<?php

namespace App\Services;

class LdapService
{
    public function authenticate(string $email, string $password): array|false
    {
        $email = trim($email);

        if ($email === '' || $password === '') {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $connection = ldap_connect(
            'ldap://' . LDAP_HOST . ':'. LDAP_PORT
        );

        if ($connection === false) {
            return false;
        }

        // Configuration LDAP.
        ldap_set_option(
            $connection,
            LDAP_OPT_PROTOCOL_VERSION,
            3
        );

        ldap_set_option(
            $connection,
            LDAP_OPT_REFERRALS,
            0
        );

        $filter = '(&(objectClass=inetOrgPerson)(mail=' .
            ldap_escape($email, '', LDAP_ESCAPE_FILTER) .
            '))';

        $search = @ldap_search(
            $connection,
            LDAP_BASE_DN,
            $filter,
            ['dn', 'mail', 'cn']
        );

        if ($search === false) {
            ldap_unbind($connection);
            return false;
        }

        $entries = ldap_get_entries(
            $connection,
            $search
        );

        if ($entries === false || $entries['count'] !== 1) {
            ldap_unbind($connection);
            return false;
        }

        $userDn = $entries[0]['dn'];

        $authenticated = @ldap_bind(
            $connection,
            $userDn,
            $password
        );

        if (!$authenticated) {
            ldap_unbind($connection);
            return false;
        }

        $user = [
            'email' => $entries[0]['mail'][0] ?? $email,
            'cn'    => $entries[0]['cn'][0] ?? $email,
        ];

        ldap_unbind($connection);

        return $user;
    }
}