import React, { useState } from 'react';
import { Feather, Fontisto, SimpleLineIcons, MaterialCommunityIcons, MaterialIcons } from '@expo/vector-icons';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { StyleSheet, View, Text, Platform, getFocusedRouteNameFromRoute, Pressable } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';

// Screens for Coach
import MenuC from '../screens/Coach/Menu';
import TeamC from '../screens/Coach/Team';
import CalendarC from '../screens/Coach/Calendar';
import ProfileC from '../screens/Coach/Profile';
import ChatC from '../screens/Coach/Chat';

// import TeamCopy from '../screens/Coach/TeamCopy';

// Screens for Player
import MenuP from '../screens/Player/Menu';
import TeamP from '../screens/Player/Team';
import CalendarP from '../screens/Player/Calendar';
import ProfileP from '../screens/Player/Profile';
// import ChatP from '../screens/Player/ChatTeste.js';
import ChatP from '../screens/Player/Chat';

import { useUser } from '../components/Context/UserContext';
import { useRefresh } from '../components/Context/RefreshContext';

const Tab = createBottomTabNavigator();

const screenOptions = {
    tabBarShowLabel: false,
    headerShown: false,
    tabBarStyle: {backgroundColor: "#041b2b"}
}

// export function CoachTabRoutes({ userData }){
export function CoachTabRoutes( {setIsLogged} ){
    const {dataUser} = useUser();
    const {setLoggedUser} = useUser();
    const {isRefreshPressed} = useRefresh();
    // const routeName = getFocusedRouteNameFromRoute(route);
    // console.log('TabRoutes: ',dataUser);
    // console.log('Routes Refresh: ', isRefreshPressed);


    // if data of user logged its emplty then will get the data from storage if has data
    if(dataUser == null){
        
        const check = async () => {
                // Check if user data is present in AsyncStorage
                const userDataFromStorage = await AsyncStorage.getItem('userData');
                if (userDataFromStorage) {
                  const data = JSON.parse(userDataFromStorage);
                //   console.log("Data Storage: ", data)
          
                  setLoggedUser({idCoach: data.idCoach, age: data.age, nacionality: data.nacionality, 
                    img: data.img, idClub: data.idClub, phoneNumber: data.phoneNumber,
                    idUser: data.idUser, idClothingSize: data.idClothingSize, typeUser: data.typeUser, 
                    email: data.email, firstName: data.firstName, lastName: data.lastName, 
                    username: data.username, idTeam: data.idTeam});
                }
        };
        check();
    }

    // console.log('After:',dataUser);

    return(
        <>
        {dataUser && (
            <Tab.Navigator screenOptions={screenOptions}>
            <Tab.Screen 
                name='Team'
                // component={() => <TeamC userData={userData} />}
                options={{
                    tabBarIcon: ({color, size}) => (
                        <Feather name='users' color={color} size={size} /> // Use Lucide icon as tabBarIcon
                        ), tabBarHideOnKeyboard: true,
                        tabBarActiveTintColor: '#f50443',
                        tabBarInactiveTintColor: 'white'
                    }}
                listeners={{
                    tabPress: e => {
                        // add your conditions here
                        if(isRefreshPressed){
                            e.preventDefault(); // <-- this function blocks navigating to screen
                        }
                      },
                }}
            >
                {() => <TeamC userData={dataUser} />}
            </Tab.Screen>
            <Tab.Screen 
                name="Calendar" 
                // component={() => <CalendarC userData={userData} />}
                options={{
                    tabBarIcon: ({color, size}) => (
                        <Feather name='calendar' color={color} size={size} /> // Use Lucide icon as tabBarIcon
                    ), tabBarHideOnKeyboard: true,
                    tabBarActiveTintColor: '#f50443',
                    tabBarInactiveTintColor: 'white'
                }}
                listeners={{
                    tabPress: e => {
                        // add your conditions here
                        if(isRefreshPressed){
                            e.preventDefault(); // <-- this function blocks navigating to screen
                        }
                      },
                }}
            >
                {() => <CalendarC userData={dataUser} />}
            </Tab.Screen>
            <Tab.Screen 
                name="Menu" 
                // component={() => <MenuC userData={userData} />}
                options={{
                    tabBarIcon: ({focused}) => {
                        return(
                            <View
                                style={{
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    backgroundColor: '#041b2b',
                                    // backgroundColor: '#041b2b',
                                    height: Platform.OS == "ios" ? 50 : 60,
                                    width: Platform.OS == "ios" ? 50 : 60,
                                    top: Platform.OS == "ios" ? -10 : -20,
                                    borderRadius: Platform.OS == "ios" ? 25 : 30,
                                    borderWidth: 2,
                                    borderColor: 'white',
                                }}
                            >
                                <Feather name='bell' size={24} color={focused ? '#f50443' : 'white'} />
                            </View>
                        )
                    },tabBarOptions: {
                        tabBarHideOnKeyboard: true,
                        tabBarActiveTintColor: '#f50443',
                        tabBarInactiveTintColor: 'white',
                      }      
                }}
                listeners={{
                    tabPress: e => {
                        // add your conditions here
                        if(isRefreshPressed){
                            e.preventDefault(); // <-- this function blocks navigating to screen
                        }
                      },
                }}
            >
                {() => <MenuC userData={dataUser} />}
            </Tab.Screen>
            <Tab.Screen 
                name="Chat" 
                // component={() => <ChatC userData={userData} />}
                options={{
                    tabBarIcon: ({color, size}) => (
                        <Feather name='message-square' color={color} size={size} /> // Use Lucide icon as tabBarIcon
                    ), tabBarHideOnKeyboard: true,
                    tabBarActiveTintColor: '#f50443',
                    tabBarInactiveTintColor: 'white'
                }}
                listeners={{
                    tabPress: e => {
                        // add your conditions here
                        if(isRefreshPressed){
                            e.preventDefault(); // <-- this function blocks navigating to screen
                        }
                      },
                }}
            >
                {/* {() => <ChatC userData={userData} teamId={userData.idTeam} />} */}
                {() => <ChatC userData={dataUser} idTeam={dataUser.idTeam} />}
            </Tab.Screen>
            <Tab.Screen 
                name='Profile'
                // component={() => <ProfileC userData={userData} />}
                options={{
                    tabBarIcon: ({color, size}) => (
                        <Feather name='user' color={color} size={size} /> // Use Lucide icon as tabBarIcon
                    ), tabBarHideOnKeyboard: true,
                    tabBarActiveTintColor: '#f50443',
                    tabBarInactiveTintColor: 'white' 
                }}
                listeners={{
                    tabPress: e => {
                        // add your conditions here
                        if(isRefreshPressed){
                            e.preventDefault(); // <-- this function blocks navigating to screen
                        }
                      },
                }}
            >
                    {() => <ProfileC userData={dataUser} setIsLogged={setIsLogged}/>}
            </Tab.Screen>
        </Tab.Navigator>
        )}
        </>
    )
}


// export function PlayerTabRoutes({ userData }){
export function PlayerTabRoutes( {setIsLogged} ){
    const {dataUser} = useUser();
    const {setLoggedUser} = useUser();
    const {isRefreshPressed} = useRefresh();

    if(dataUser == null){
        const check = async () => {
            // Check if user data is present in AsyncStorage
            const userDataFromStorage = await AsyncStorage.getItem('userData');
            if (userDataFromStorage) {
                const data = JSON.parse(userDataFromStorage);
            //   console.log("Data Storage: ", data)
        
                setLoggedUser({idPlayer: data.idPlayer, age: data.age, nacionality: data.nacionality, 
                weight: data.weight, imc: data.imc, phoneNumber: data.phoneNumber, salary: data.salary, 
                img: data.img, idClub: data.idClub, idUser: data.idUser, idClothingSize: data.idClothingSize, 
                typeUser: data.typeUser, email: data.email, firstName: data.firstName, lastName: data.lastName, 
                username: data.username, position: data.position, state: data.state, 
                idTeam: data.idTeam});
                // setTeamId(data.idTeam);
            }
            // console.log("data from storage: ", userDataFromStorage);
        };
        check();
        
        // setTeamId(userData.idTeam);
    }

    
    return(
        <>
            {dataUser && (
                <Tab.Navigator screenOptions={screenOptions}>
                <Tab.Screen 
                    name='Team'
                    // component={() => <TeamP userData={userData} />}
                    options={{
                        tabBarIcon: ({color, size}) => (
                            <Feather name='users' color={color} size={size} /> // Use Lucide icon as tabBarIcon
                            ), tabBarHideOnKeyboard: true,
                            tabBarActiveTintColor: '#f50443',
                            tabBarInactiveTintColor: 'white'
                        }}
                    listeners={{
                        tabPress: e => {
                            // add your conditions here
                            if(isRefreshPressed){
                                e.preventDefault(); // <-- this function blocks navigating to screen
                            }
                          },
                    }}
                >
                        {() => <TeamP userData={dataUser} />}
                </Tab.Screen>
                <Tab.Screen 
                    name="Calendar" 
                    // component={() => <CalendarP userData={userData} />}
                    options={{
                        tabBarIcon: ({color, size}) => (
                            <Feather name='calendar' color={color} size={size} /> // Use Lucide icon as tabBarIcon
                            ), tabBarHideOnKeyboard: true,
                            tabBarActiveTintColor: '#f50443',
                            tabBarInactiveTintColor: 'white'
                        }}
                    listeners={{
                        tabPress: e => {
                            // add your conditions here
                            if(isRefreshPressed){
                                e.preventDefault(); // <-- this function blocks navigating to screen
                            }
                          },
                    }}
                >
                        {() => <CalendarP userData={dataUser} />}
                </Tab.Screen>
                <Tab.Screen 
                    name="Menu" 
                    // component={() => <MenuP userData={userData} />}
                    options={{
                        tabBarIcon: ({focused}) => {
                            return(
                                <View
                                    style={{
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        backgroundColor: '#041b2b',
                                        // backgroundColor: '#041b2b',
                                        height: Platform.OS == "ios" ? 50 : 60,
                                        width: Platform.OS == "ios" ? 50 : 60,
                                        top: Platform.OS == "ios" ? -10 : -20,
                                        borderRadius: Platform.OS == "ios" ? 25 : 30,
                                        borderWidth: 2,
                                        borderColor: 'white',
                                    }}
                                >
                                    <Feather name='bell' size={24} color={focused ? '#f50443' : 'white'} />
                                </View>
                            )
                        },tabBarOptions: {
                            tabBarHideOnKeyboard: true,
                            tabBarActiveTintColor: '#f50443',
                            tabBarInactiveTintColor: 'white',
                          }      
                    }}
                    listeners={{
                        tabPress: e => {
                            // add your conditions here
                            if(isRefreshPressed){
                                e.preventDefault(); // <-- this function blocks navigating to screen
                            }
                          },
                    }}
                >
                        {() => <MenuP userData={dataUser} />}
                </Tab.Screen>
                <Tab.Screen 
                    name="Chat" 
                    // component={() => <ChatP userData={userData} />}
                    options={{
                        tabBarIcon: ({color, size}) => (
                            <Feather name='message-square' color={color} size={size} /> // Use Lucide icon as tabBarIcon
                            ), tabBarHideOnKeyboard: true,
                            tabBarActiveTintColor: '#f50443',
                            tabBarInactiveTintColor: 'white'
                        }}
                    listeners={{
                        tabPress: e => {
                            // add your conditions here
                            if(isRefreshPressed){
                                e.preventDefault(); // <-- this function blocks navigating to screen
                            }
                          },
                    }}
                >
                        {() => <ChatP userData={dataUser} idTeam={dataUser.idTeam} />}
                </Tab.Screen>
                <Tab.Screen 
                    name='Profile'
                    // component={() => <ProfileP userData={userData} />}
                    options={{
                        tabBarIcon: ({color, size}) => (
                            <Feather name='user' color={color} size={size} /> // Use Lucide icon as tabBarIcon
                            ), tabBarHideOnKeyboard: true,
                            tabBarActiveTintColor: '#f50443',
                            tabBarInactiveTintColor: 'white'
                        }}
                    listeners={{
                        tabPress: e => {
                            // add your conditions here
                            if(isRefreshPressed){
                                e.preventDefault(); // <-- this function blocks navigating to screen
                            }
                          },
                    }}
                >
                        {() => <ProfileP userData={dataUser} setIsLogged={setIsLogged} />}
                </Tab.Screen>
            </Tab.Navigator>
            )}
        </>
    )
}