import React from 'react';
import { View } from 'react-native';
import { Email } from 'react-native-email';

const SendByEmail = (email, link, body) => {
  Email.send({
    email,
    link,
    body
  }).catch(console.error);
}

export default SendByEmail;