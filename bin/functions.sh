#!/usr/bin/env bash

set_title () {
    echo -e "\033]0;${1}\007"
}

header () {
    printf "${CYAN}${1}${NC}\n"
}

get_realpath () {
    if [[ -x "$(command -v realpath)" ]]
    then
      path="$(realpath ${1})"
    else
      path=${1}
    fi

    echo "${path}"
}

check_if_dir_exists  () {
    DIRECTORY=$(echo $(get_realpath "${1}"))
    if [[ -d "${DIRECTORY}" && ! -L "${DIRECTORY}" ]]
    then
        return 1
    fi
    return 0
}

accepted_value () {
    printf "${DARK}Accepted value: '${1}'${NC}\n\n"
}

dark () {
    printf "${DARK}${1}${NC}\n"
}

info () {
    printf "\n${GREEN}${1}${NC}\n"
}

green () {
    printf "${GREEN}${1}${NC}\n"
}

yellow () {
    printf "${YELLOW}${1}${NC}\n"
}

light_yellow () {
    printf "${LIGHT_YELLOW}${1}${NC}\n"
}

red () {
    printf "${RED}${1}${NC}\n"
}

light_green () {
    printf "${LIGHT_GREEN}${1}${NC}\n"
}

error () {
    printf "\n${RED}${1}${NC}\n\n"
}

comment () {
    printf "\n${YELLOW}${1}${NC}\n"
}

no-exec () {
    comment "No-Exec..."
}

is_active () {
    if [[ ${1} == 1 ]]
    then
        enabled "${2}"
    else
        disabled "${2}"
    fi
}

enabled () {
    echo -e "[${LIGHT_GREEN} ON ${NC}]  ${1}"
}

disabled () {
    echo -e "[${DARK} -- ${NC}]  ${1}"
}

help_message () {
if [[ ${HELP} == 1 ]]
then
    echo "Options:"
    echo "  --help          - show this message"
    [[ $OPTION_NO_RESTART ]] && echo "  --no-restart    - do not restart container (may cause 'No coverage driver' and/or 'It seems like *app* is not installed.')"
    [[ $OPTION_PHPUNIT ]] && echo "  --unit          - enable phpunit"
    [[ $OPTION_ANALYZE ]] && echo "  --analyze       - enable analysis(PHPStan, Psalm)"
    [[ $OPTION_METRICS ]] && echo "  --metrics       - enable PHPMetrics"
    [[ $OPTION_MULTI_TEST ]] && echo "  --multi         - enable multi-test"
    [[ $OPTION_COVERAGE ]] && echo "  --coverage      - enable code coverage(PHPUnit)"
    [[ $OPTION_ALL ]] && echo "  --all           - enable analysis, phpunit with code coverage and code_sniffer with beautifier (PHPMetrics and multi-tester disabled)"
    [[ $OPTION_BEAUTY ]] && echo "  --beautify      - enable code beautifier"
    [[ $OPTION_BEAUTY ]] && echo "  --beauty        - same as above"
    [[ $OPTION_PROPAGATE ]] && echo "  --propagate     - propagate unrecognized options to underlying script"
    if [[ ${PROPAGATE} == 0 ]]
    then
        exit 0
    fi
fi
}

setup_help_message () {
if [[ ${HELP} == 1 ]]
then
    echo "Usage:"
    echo "./setup owner name \"Your Name\""
    echo "owner                  - package owner"
    echo "name                   - package name"
    echo "\"Your Name\"          - package owner name"
    echo "Options:"
    echo "  --help               - show this message"
    exit 0
fi
}

options_enabled () {
    is_active ${RESTART_CONTAINER} "Container restart"
    is_active ${ANALYZE} "Analysis"
    is_active ${METRICS} "PHPMetrics"
    is_active ${MULTI_TEST} "Multi-tester"
    is_active ${PHPUNIT} "PHPUnit"
    is_active ${COVERAGE} "Coverage"
    is_active ${BEAUTY} "Beautifier"
}

generate_report_file () {
    echo "<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <title>${HEADER}</title>
</head>
<body>

<h1>Report &lt;${HEADER}&gt;</h1>

<p>Some links could be empty</p>
<a href='${TMP_DIR_PARTIAL}/${COVERAGE_DIR}/html/index.html'>Coverage report</a><br>
<a href='${TMP_DIR_PARTIAL}/${PHPMETRICS_DIR}/index.html'>Phpmetrics report</a><br>

</body>
</html>" > ${TEST_REPORT_INDEX}
}

capitalize_every_word () {
    echo ${1} | sed -r 's/\<./\U&/g'
}

remove_spaces () {
    echo ${1// /}
}

remove_symbols () {
    echo ${1//[-_ ]/}
}

replace_symbols_by_space () {
    echo ${1//[-_]/ }
}

remove_prefix () {
    prefix="${1}"
    result="${2}"
    if [[ ${result} == *"${prefix}"* ]]
    then
        result=${result#"$prefix"}
    fi
    echo "${result}"
}

remove_suffix () {
    suffix="${1}"
    result="${2}"
    if [[ ${result} == *"${suffix}"* ]]
    then
        result=${result%"$suffix"}
    fi
    echo "${result}"
}

select_owner_namespace () {
    PS3='Please enter your choice of owner namespace: '
    op1="No namespace"
    op2="'${1}'"
    op3="Enter your variant"
    options=("${op1}" "${op2}" "${op3}")
    package_owner_namespace=""
    select opt in "${options[@]}"
    do
        case ${opt} in
            "${op1}")
                package_owner_namespace=""
                break
                ;;
            "${op2}")
                package_owner_namespace="${1}"
                break
                ;;
            "${op3}")
                read -e -p "Enter package owner namespace [${package_owner_namespace}]: " input
                package_owner_namespace=$(replace_symbols_by_space "${input:-$package_owner_namespace}")
                package_owner_namespace=$(capitalize_every_word "${package_owner_namespace}")
                package_owner_namespace=$(remove_spaces "${package_owner_namespace}")
                break
                ;;
            *)
                ;;
        esac
    done
    echo "${package_owner_namespace}"
}

read_value () {
    cr=`echo $'\n.'`
    cr=${cr%.}
    result=${3}
    value_name=${1}
    comment=${2}
    notice=${4}
    if [[ ! ${notice} == "" ]]
    then
        notice=$(light_yellow "${notice}")
        notice="${notice}${cr}"
    fi
    if [[ ! ${comment} == "" ]]
    then
        comment=$(dark "(${comment})")
    fi
    comment=$(echo "Enter ${value_name}:${cr}${comment}")
    read -p "${notice}${comment}${cr}[${result}] " input
    result="${input:-$result}"
    echo "${result}"
}

enter_package_dir() {
    cr=`echo $'\n.'`
    cr=${cr%.}
    package_dir=$(read_value "package directory" "'${2}' -> '${1}' ?" "${1}" "It should be new non-existent yet dir name!")
    echo ${package_dir}
}

check_git_user () {
    git_user_email="$(git config user.email)"
    if [[ -z "$git_user_email" ]]
    then
        return 1
    fi
    git_user_name="$(git config user.name)"
    if [[ -z "$git_user_name" ]]
    then
        return 1
    fi
    return 0
}
