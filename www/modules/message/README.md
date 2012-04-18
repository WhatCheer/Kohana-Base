# Message

A flash messaging system for Kohana v3.0 and higher.

To use, download the source, extract and rename to message. Move that folder into your modules directory and activate in your bootstrap.

This is a fork of https://github.com/daveWid/message and is API compatible, but is not view compatible.

## Usage

To set a flash message all it takes is the following

    Message::set( $type, $message, $headline );

Headline is optional.

## Wrapper methods

There are also convienience methods that are wrappers for the different types of messages.

    Message::error( $message, $headline );
    Message::success( $message, $headline );
    Message::notice( $message, $headline );
    Message::warn( $message, $headline );


When you need to get a message you can:

    echo Message::display(); 
    // or...
    echo Message::render();

## Messages

There are 4 constants you can use to set a message

    Message::ERROR = 'error'
    Message::NOTICE = 'notice'
    Message::SUCCESS = 'success'
    Message::WARN = 'warn'

