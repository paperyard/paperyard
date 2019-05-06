#!/usr/bin/env bash

# A POSIX variable
# Reset in case getopts has been used previously in the shell.
OPTIND=1

mute=false
overwrite=false

while getopts "mo" opt; do
    case "$opt" in
    m)  mute=true
        ;;
    o)  overwrite=true
        ;;
    esac
done

# some getopts magic (i guess)
shift $((OPTIND-1))
[ "${1:-}" = "--" ] && shift

# generate
# TODO: shasum is mac specific
sql_password=$(head -c 32 /dev/urandom | shasum -a 256 | awk '{print $1}')

if [[ ${mute} = false ]]; then
    echo "Setting MariaDB root password to:"
    echo ${sql_password}
fi

if [[ ${overwrite} = true ]]; then
    sed -i "s/MYSQL_ROOT_PASSWORD:.*/MYSQL_ROOT_PASSWORD: ${sql_password}/g" docker-compose.yml
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${sql_password}/g" app/.env
else
    sed -i "s/MYSQL_ROOT_PASSWORD:\s*$/MYSQL_ROOT_PASSWORD: ${sql_password}/g" docker-compose.yml
    sed -i "s/DB_PASSWORD=\s*$/DB_PASSWORD=${sql_password}/g" app/.env
fi
