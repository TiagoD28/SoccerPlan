// import 'react-native-gesture-handler';
import {StyleSheet} from 'react-native';
import React, { useEffect, useState } from 'react';
import Toast from 'react-native-toast-message';
import {Routes, RoutesAuthentication, CoachRoutes, PlayerRoutes} from './routes/index'
import ToastConfig from './components/Toast/ToastStyle';
import Splash from './components/Splash';
import { ThemeProvider } from './components/Context/ThemeContext';
import {UserProvider, UserConsumer } from './components/Context/UserContext';
import { RefreshProvider } from './components/Context/RefreshContext';
import { MessageProvider } from './components/Context/MessageContext';
// import { EventsProvider } from './components/Context/EventsContext';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { Message } from 'react-native-gifted-chat';
import { TeamProvider } from './components/Context/TeamContext';


export default function App(){
  const [loadingComplete, setLoadingComplete] = useState(false);
  const [isLogged, setIsLogged] = useState(false);
  // const { isLogged, setLoggedUser } = useUser();
  const [typeUser, setTypeUser] = useState(null);

  const handleLoadingComplete = () => {
    setLoadingComplete(true);
  }

  const handleLogged = async (userData) => {
    setIsLogged(true);
    setTypeUser(userData.typeUser);

    // Save user data to AsyncStorage
    await AsyncStorage.setItem('userData', JSON.stringify(userData));
  }

  useEffect(() => {
    const checkUserLogin = async () => {
      // Check if user data is present in AsyncStorage
      const userDataFromStorage = await AsyncStorage.getItem('userData');
      if (userDataFromStorage) {
        const userData = JSON.parse(userDataFromStorage);
        setIsLogged(true);
        setTypeUser(userData.typeUser);

        console.log('User data from storage', userDataFromStorage);
      }
      // console.log("data from storage: ", userDataFromStorage);
    };
    checkUserLogin();
  }, []);

  

  // console.log('App isLogeed: ', isLogged);

  return(
    <>
    <ThemeProvider>
      <UserProvider>
        <RefreshProvider>
          <TeamProvider>
          {/* <MessageProvider> */}
          {/* <EventsProvider> */}
            {!loadingComplete ? (
              <Splash onLoaded={handleLoadingComplete} />
            ) : (
              !isLogged ? (
                <>
                  <RoutesAuthentication onLogged={handleLogged} />
                  {/* <RoutesAuthentication /> */}
                  <Toast config={ToastConfig} ref={(ref) => Toast.setRef(ref)} />
                  {/* <ToastComponent /> */}
                </>
              ) : (
                <>
                  {/* {(userData.typeUser === 'Coach' || userData.typeUser === 'Admin') ? ( */}
                  {(typeUser === 'Coach' || typeUser === 'Admin') ? (
                    <>
                      {/* <CoachRoutes userData={userData} /> */}
                      <CoachRoutes setIsLogged={setIsLogged}/>
                      {/* <Settings onLogout={handleLogout} /> */}
                      {/* <Toast config={ToastConfig} ref={(ref) => Toast.setRef(ref)} /> */}
                    </>
                  ) : (
                    <>
                      {/* <PlayerRoutes userData={userData} /> */}
                      <PlayerRoutes setIsLogged={setIsLogged}/>
                      {/* <CoachRoutes /> */}
                      {/* <Toast config={ToastConfig} ref={(ref) => Toast.setRef(ref)} /> */}
                    </>
                  )}
                  {/* <ToastComponent /> */}
                  <Toast config={ToastConfig} ref={(ref) => Toast.setRef(ref)} />
                </>
              )
            )}
            {/* </EventsProvider> */}
            {/* </MessageProvider> */}
            </TeamProvider>
          </RefreshProvider>
        </UserProvider>
    </ThemeProvider>
  </>
  );
}