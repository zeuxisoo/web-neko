#!/bin/bash

RESOURCES_VIEWS_PATH=resources/views
OPEN_STATUS_FILE=$RESOURCES_VIEWS_PATH/.openning

function open_home {
    if [ ! -f $OPEN_STATUS_FILE ]; then
        # Append .close suffix to close page
        mv $RESOURCES_VIEWS_PATH/index.blade.php $RESOURCES_VIEWS_PATH/index.blade.php.close

        # Remove .open suffix from open page
        mv $RESOURCES_VIEWS_PATH/index.blade.php.open $RESOURCES_VIEWS_PATH/index.blade.php

        clean_views_cache

        touch $OPEN_STATUS_FILE

        echo "OK"
    else
        echo "Error! The page is not close"
    fi
}

function close_home {
    if [ -f $OPEN_STATUS_FILE ]; then
        # Append .open suffix to open page
        mv $RESOURCES_VIEWS_PATH/index.blade.php $RESOURCES_VIEWS_PATH/index.blade.php.open

        # Remove .close suffix from close page
        mv $RESOURCES_VIEWS_PATH/index.blade.php.close $RESOURCES_VIEWS_PATH/index.blade.php

        clean_views_cache

        rm -rf $OPEN_STATUS_FILE

        echo "OK"
    else
        echo "Error! The page is not open"
    fi
}

function clean_views_cache {
    rm -rf storage/framework/views/*.php
}

COMMAND=${@:$OPTIND:1}

case $COMMAND in
    on)
        echo "On ..."
        open_home
    ;;
    off)
        echo "Off ..."
        close_home
    ;;
    *)
        echo "- on"
        echo "- off"
    ;;
esac
