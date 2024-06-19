import React, { useState, useRef, useEffect } from 'react';
import { StyleSheet, TouchableOpacity, TextInput, View, Text, Dimensions, Animated } from 'react-native';

import AsyncStorage from '@react-native-async-storage/async-storage';
import Icon from 'react-native-vector-icons/Feather';
import sendDataToApi from '../../getPostApi/sendDataToApi';
import { useTheme } from '../../components/Context/ThemeContext';
import { useUser } from '../Context/UserContext';
import { useRefresh } from '../Context/RefreshContext';

// import { Container } from './styles';

export default function Refresh({userLogged}) {
  // const userLogged = userData;
  // console.log(userLogged);
    const { setLoggedUser } = useUser();
    const { dataUser } = useUser();
    const { isRefreshPressed, setRefreshPressed } = useRefresh();

    const spinValue = useRef(new Animated.Value(0)).current;

    useEffect(() => {
        startSpinAnimation();
        fetchData();
      }, [isRefreshPressed]);
    
      const startSpinAnimation = async () => {
        Animated.timing(spinValue, {
          toValue: 1,
          duration: 1000, // You can adjust the duration as needed
          useNativeDriver: true,
        }).start(() => {
          spinValue.setValue(0);
          startSpinAnimation(); // Start the animation again for an infinite loop
        });
      };

      const fetchData = async () => {
        console.log('Refresh: ', dataUser);
        const apiResponse = await sendDataToApi('Users', 'getInfoUpdatedUser', {'idUser': dataUser.idUser})
        console.log('Start animation');
        if(apiResponse.status === '200'){
          const data = apiResponse['data'];
          await AsyncStorage.setItem('userData', JSON.stringify(apiResponse.data));
          // console.log(userLogged);
          if(dataUser.idCoach){
            setLoggedUser({idCoach: data.idCoach, age: data.age, nacionality: data.nacionality, 
              salary: data.salary, img: data.img, idClub: data.idClub, 
              idUser: data.idUser, idClothingSize: data.idClothingSize, typeUser: data.typeUser, 
              email: data.email, firstName: data.firstName, lastName: data.lastName, 
              username: data.username, idTeam: data.idTeam});
  
          } else if(dataUser.idPlayer){
            setLoggedUser({idPlayer: data.idPlayer, age: data.age, nacionality: data.nacionality, 
              weight: data.weight, imc: data.imc, numPhone: data.numPhone, salary: data.salary, 
              img: data.img, idClub: data.idClub, idUser: data.idUser, idClothingSize: data.idClothingSize, 
              typeUser: data.typeUser, email: data.email, firstName: data.firstName, lastName: data.lastName, 
              username: data.username, position: data.position, state: data.state, 
              idTeam: data.idTeam});
  
          }
        } else {
          console.log(apiResponse.message);
        }
      }

    return (
        <>
            <View style={styles.refreshing}>
              <Text style={{color: 'white'}}>Refresh: </Text>
                <Animated.View
                style={{
                  transform: [{ rotate: spinValue.interpolate({ inputRange: [0, 1], outputRange: ['0deg', '360deg'] }) }],
                }}
              >
                <Text>
                <Icon style={styles.headerIcon} name="refresh-cw" size={25} color={'white'} />
                </Text>
              </Animated.View>
            </View>
        </>
  );
}

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
    refreshing:{
        flex: 1,
        width: windowWidth,
        height: windowHeight - 100,
        backgroundColor: '#041b2b',
        opacity: 0.5,
        justifyContent: 'center', 
        alignItems: 'center',
    },
    btnLogout: {
        width: '50%',
        height: 50,
        alignItems: 'center',
        justifyContent: 'center',
        marginTop: 60,
        borderWidth: 1,
    },
})