import React, { useState } from 'react';
import { StyleSheet, TouchableOpacity, TextInput, View, Text, Dimensions } from 'react-native';
import { useTheme } from '../../components/Context/ThemeContext';
import { useUser } from '../Context/UserContext';
import AsyncStorage from '@react-native-async-storage/async-storage';

// import { Container } from './styles';

export default function Settings({userLogged, cancelSettings, onLogout, setIsLogged}) {
    const [theme, setTheme] = useTheme();
    const { setLoggedUser } = useUser();
    const { dataUser } = useUser();
    const containerStyle = theme === 'dark' ? styles.darkContainer : styles.lightContainer;
    // console.log(userLogged);

    // const toggleTheme = () => {
    //     const newTheme = theme === 'light' ? 'dark' : 'light';
    //     setTheme(newTheme);
    //     console.log(newTheme);
    // }

    const logout = async () => {

        await AsyncStorage.removeItem('userData');

        setLoggedUser(null);
        setIsLogged(false);
        // onLogout(false); // Call the callback function to update isLogged in App.js
    }

    return (
        <>
            <View style={containerStyle}>
                {/* <Text>Settigs</Text>
                <TouchableOpacity onPress={toggleTheme}>
                    <Text>Change Theme</Text>
                </TouchableOpacity> */}
                {/* <Text>{setTheme}</Text> */}
                {/* <TouchableOpacity onPress={cancelSettings}>
                    <Text>Back</Text>
                </TouchableOpacity> */}
                <TouchableOpacity style={styles.btnLogout} onPress={() => logout()}>
                    <Text style={styles.text}>Logout</Text>
                </TouchableOpacity>
            </View>
        </>
  );
}

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
    lightContainer:{
        flex: 1,
        height: windowHeight - 185,
        backgroundColor: 'white',
        justifyContent: 'center', 
        alignItems: 'center'
    },
    darkContainer:{
        flex: 1,
        height: windowHeight - 185,
        backgroundColor: '#041b2b',
        justifyContent: 'center', 
        alignItems: 'center'
    },
    btnLogout: {
        marginTop: 16,
        backgroundColor: '#f50443',
        padding: 12,
        borderRadius: 8,
        alignItems: 'center',
        width: '50%'
    },
    text: {
        color: 'white',
        fontSize: 15
    }
})