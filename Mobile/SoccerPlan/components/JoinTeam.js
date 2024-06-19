import React from "react";
import {View, Text, StyleSheet, Dimensions, Button, TouchableOpacity, TouchableHighlight} from 'react-native';
import Icon from 'react-native-vector-icons/Feather';

export default function JoinTeam({ onJoinTeamPressed, editable }){
    return(
        <>
        {editable ?(
            <View style={styles.container}>
                <View style={styles.cardBody}>
                    <Text style={styles.tilte}>Team</Text>
                    <Text style={styles.text}>Join a Team and enjoy</Text>
                    <View>
                        <Icon name="users" size={35} color={'white'} marginTop={20} />
                    </View>
                    <View style={styles.cardBtn}>
                        <TouchableHighlight  style={styles.btn} underlayColor={"#f50443"} onPress={onJoinTeamPressed}>
                            <Text style={{color: 'white'}}>Join a Team</Text>
                        </TouchableHighlight>
                    </View>
                </View>
            </View>
        ) : (
        <>
            <View style={styles.container}>
                <View style={styles.cardBody}>
                    <Text style={styles.tilte}>Team</Text>
                    <Text style={styles.text}>Join a Team and enjoy</Text>
                    <View>
                        <Icon name="users" size={35} color={'white'} marginTop={20} />
                    </View>
                    <View style={styles.cardBtn}>
                        <TouchableOpacity  style={styles.btn}>
                            <Text style={{color: 'white'}}>Join a Team</Text>
                        </TouchableOpacity>
                    </View>
                </View>
                <View style={styles.containerOpacity} >
                {/* Content of the overlay goes here */}
                </View>
            </View>
        </>
        )}
    </>
    )
}

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
    containerOpacity: {
        position: 'absolute',
        width: windowWidth,
        height: 325,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: "white",
        opacity: 0.5,
    },
    container: {
        flexDirection: 'column',
        width: windowWidth,
        height: 325,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: "white"
    },
    cardBody: {
        alignItems: 'center',  
        width: '90%', // Adjust the width as needed based on your design
        height: '75%',
        borderRadius: 8,
        padding: 16,
        marginVertical: 8,
        backgroundColor: '#041b2b',
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