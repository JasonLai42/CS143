#!/usr/bin/env bash
ZIP_FILE=project2.zip
REQUIRED_FILES="index.php sql/create.sql sql/load.sql"

TMP_NAME=project2
TMP_DIR=/tmp/${TMP_NAME}
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

#error function
function error_exit()
{
   echo -e "ERROR: $1" 1>&2
   rm -rf ${TMP_DIR}
   exit 1
}

# make sure running in container
if [ `whoami` != "cs143" ]; then
    error_exit "You need to run this script within the container"
fi

# clean any existing files
rm -rf ${TMP_DIR}
mkdir ${TMP_DIR}

# change to the container contains this script
cd ${DIR}


# check file existence
if [ -f ${ZIP_FILE} ]; then
    rm -f ${ZIP_FILE}
fi

# check the existence of the required files
for FILE in ${REQUIRED_FILES}
do
    if [ ! -f ${FILE} ]; then
        echo "ERROR: Cannot find ${FILE} in ${DIR}" 1>&2
        exit 1
    fi
done

zip ${ZIP_FILE} -x p2_package -x p2_test -r .
if [ $? -ne 0 ]; then
    error_exit "Create ${ZIP_FILE} failed, check for error messages in console."
fi

echo "[SUCCESS] Created '$DIR/${ZIP_FILE}'"

exit 0
