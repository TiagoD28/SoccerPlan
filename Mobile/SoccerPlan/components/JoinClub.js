import React, { useState } from "react";
import {View, Text, StyleSheet, Dimensions, Button, TouchableOpacity, TouchableHighlight} from 'react-native';
import Icon from 'react-native-vector-icons/Feather';
import showToast from "./Toast/Toast";

export default function JoinClub({ onJoinClubPressed }){

    return(
        <View style={styles.container}>
            <View style={styles.cardBody}>
                <Text style={styles.tilte}>Club</Text>
                <Text style={styles.text}>Join a Club and enjoy</Text>
                <View>
                    <Icon name="users" size={35} color={'white'} marginTop={20} />
                </View>
                <View style={styles.cardBtn}>
                    <TouchableHighlight  style={styles.btn} underlayColor={"#f50443"} onPress={onJoinClubPressed}>
                        <Text style={{color: 'white'}}>Join a Club</Text>
                    </TouchableHighlight>
                </View>
            </View>
        </View>
    )
}

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
    container: {
        flexDirection: 'column',
        width: windowWidth,
        height: 325,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: "white",
        // borderBottomWidth: 1,
        // borderBottomColor: 'gray'
    },
    cardBody: {
        alignItems: 'center',  
        width: '90%', // Adjust the width as needed based on your design
        height: '75%',
        borderRadius: 8,
        padding: 16,
        marginVertical: 8,
        backgroundColor: '#041b2b',
        // marginTop: 30
    },
    tilte: {
        color: 'white',
        fontSize: 20,
        fontWeight: 'bold',
    },
    text: {
        color: 'white',
        fontSize: 15,
        marginTop: 10
    },
    cardBtn: {
        width: '110%', // Adjust the width as needed based on your design
        height: 100,
        backgroundColor: '#041b2b',
        justifyContent: 'center',
        alignItems: 'center',
        marginTop: 25,
        borderTopWidth: 1,
        borderTopColor: 'gray',
        borderBottomLeftRadius: 8,
        borderBottomRightRadius: 8
    },
    btn: {
        justifyContent: 'center',
        alignItems: 'center',
        width: '60%',
        height: '40%',
        backgroundColor: '#041b2b',
        borderWidth: 1,
        borderColor: 'white',
        borderRadius: 10
    }
  });