<?php

namespace App\Services;

class LdapService
{
    public function authenticate(string $email, string $password): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if ($password === '') {
            return false;
        }

        $ldap = ldap_connect(LDAP_HOST, LDAP_PORT);

        if (!$ldap) {
            return false;
        }

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        /*
         * 1. Connexion avec le compte administrateur LDAP
         * pour rechercher l'utilisateur.
         */
        if (!@ldap_bind(
            $ldap,
            LDAP_ADMIN_DN,
            LDAP_ADMIN_PASSWORD
        )) {
            ldap_unbind($ldap);
            return false;
        }

        /*
         * 2. Recherche de l'utilisateur à partir de son email.
         */
        $filter = sprintf(
            LDAP_USER_FILTER,
            ldap_escape($email, '', LDAP_ESCAPE_FILTER)
        );

        $search = @ldap_search(
            $ldap,
            LDAP_BASE_DN,
            $filter,
            ['dn', 'mail']
        );

        if (!$search) {
            ldap_unbind($ldap);
            return false;
        }

        $entries = ldap_get_entries($ldap, $search);

        if ($entries['count'] !== 1) {
            ldap_unbind($ldap);
            return false;
        }

        $userDn = $entries[0]['dn'];

        /*
         * 3. Vérification du mot de passe de l'utilisateur.
         */
        $authenticated = @ldap_bind(
            $ldap,
            $userDn,
            $password
        );

        ldap_unbind($ldap);

        return $authenticated;
    }
}