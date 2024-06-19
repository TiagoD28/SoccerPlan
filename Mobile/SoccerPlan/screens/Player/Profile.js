// import { Weight } from 'lucide-react';
import { StyleSheet, View, Text, Button, Image, ScrollView, Dimensions} from 'react-native';
import { useState, useEffect, useRef } from 'react';
import { TouchableOpacity } from 'react-native';
import { useNavigation } from '@react-navigation/native';

import Config from '../../getPostApi/config';
import useData from '../../getPostApi/useData';
import HeaderBar from '../../components/HeaderBar/HeaderBar';
import Settings from '../../components/HeaderBar/Settings';
import EditProfile from '../../components/EditProfile';
import Refresh from '../../components/HeaderBar/Refresh';
import { useTheme } from '../../components/Context/ThemeContext';
import { useRefresh } from '../../components/Context/RefreshContext';

export default function Profile({userData, setIsLogged}) {
  const userLogged = userData;
  const [edit, setEdit] = useState(false);
  const [isSettings, setIsSettings] = useState(false);
  const [theme] = useTheme();
  const containerStyle = theme === 'dark' ? styles.darkContainer : styles.lightContainer;
  const { isRefreshPressed, setRefreshPressed } = useRefresh();

  // const navigation = useNavigation();

  const cancelSettings = () => {
    setIsSettings(!isSettings);
  }

  const handleRefreshPressed = () => {
    console.log('Refreshing...');
    setRefreshPressed(true);
    setTimeout(() => {
      setRefreshPressed(false);
      console.log('Refreshed!');
    }, 2000);
    // console.log('Refreshed');
  }

  return (
    <>
      {/* <HeaderBar screen={"Profile"} onSettingsPressed={onSettingsPressed} /> */}
      <HeaderBar userLogged={userLogged} screen={"Profile"} onSettingsPressed={() => setIsSettings(!isSettings)} onRefreshPressed={handleRefreshPressed} />
      <ScrollView>
        {isRefreshPressed ? (
          <>
          {/* Call Refresh and send data to Refresh */}
            <Refresh userLogged={userLogged} /> 
            {/* <Refresh /> */}
          </>
        ) : (
          <>
            {isSettings ? (
            <>
              <Settings setIsLogged={setIsLogged} userLogged={userLogged} cancelSettings={cancelSettings} />
            </>
            ) : (
            <>
              <View style={containerStyle}>
              {edit ? (
                <EditProfile />
              ) : (
                <>
                  <TouchableOpacity style={styles.btnImg} onPress={() => setEdit(true)}>
                    <Image 
                      style={styles.img}
                      source={{uri: 'https://afatv.pt/img/jogadores/HUGO-OLIVEIRA.png'}}
                    />
                  </TouchableOpacity>
                  <Text style={{marginTop: 10}}>{userLogged.firstName + ' ' + userLogged.lastName}</Text>
                  <Text style={{marginTop: 10}}>{userLogged.typeUser}</Text>
                  <View style={styles.cardContainer}>
                    <View style={styles.cardInfo}>
                      <View style={styles.cardLeft}>
                        <Text style={styles.titleCard}>Age:</Text>
                        <Text style={styles.txtCard}>{userLogged.age ? userLogged.age : '-'}</Text>
                      </View>
                      <View style={styles.cardCenter}>
                        <Text style={styles.titleCard}>Nationality:</Text>
                        <Text style={styles.txtCard}>{userLogged.nacionality ? userLogged.nacionality : '-'}</Text>
                      </View>
                      <View style={styles.cardRigth}>
                        <Text style={styles.titleCard}>Salary:</Text>
                        <Text style={styles.txtCard}>{userLogged.salary ? userLogged.salary : '-'}</Text>
                      </View>
                    </View>
                  </View>
                </>
              )}
            </View>
            </>
          )}
          </>
        )}
        
      </ScrollView>
    </>    
  );
}

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
  lightContainer: {
    flex: 1,
    flexDirection: 'column',
    width: windowWidth,
    height: windowHeight,
    backgroundColor: 'white',
    alignItems: 'center',
  },
  darkContainer: {
    flex: 1,
    flexDirection: 'column',
    width: windowWidth,
    height: windowHeight,
    backgroundColor: 'black',
    alignItems: 'center',
  },
  title: {
    fontSize: 22,
    fontWeight: 'bold',
    color: 'black'
  },
  btnImg: {
    alignItems: 'center',
    marginTop: 30,
  },
  img: {
    // position: "absolute",
    height: Platform.OS == "ios" ? 95 : 100,
    width: Platform.OS == "ios" ? 95 : 100,
    borderRadius: Platform.OS == "ios" ? 60 : 70,
    alignItems: 'center',
    justifyContent: 'center',
  },
  cardContainer: {
    backgroundColor: '#041b2b',
    width: 350,
    height: 120,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 15,
    borderRadius: 15,
  },
  cardInfo:{
    flexDirection: 'row',
    width: 350,
    height: 60,
    backgroundColor: '#041b2b',
    borderRadius: 15,
  },
  cardLeft: {
    width: '30%',
    alignItems: 'center',
  },
  cardCenter: {
    width: '40%',
    borderLeftWidth: 1,
    borderRightWidth: 1,
    borderLeftColor: 'gray',
    borderRightColor: 'gray',
    alignItems: 'center',
  },
  cardRigth: {
    width: '30%',
    alignItems: 'center',
  },
  titleCard: {
    color: 'white',
    marginTop: 10,
    fontSize: 15,
    fontWeight: 'bold',
  },
  txtCard: {
    color: '#f50443',
    marginTop: 10
  }
});

// // import { Weight } from 'lucide-react';
// import { StyleSheet, View, Text, Button, Image, ScrollView} from 'react-native';
// import useData from '../../getPostApi/useData';
// import { useState, useEffect } from 'react';
// import Config from '../../getPostApi/config';
// import HeaderBar from '../../components/HeaderBar';
// // import { Bell } from 'lucide-react-native';

// export default function Profile({userData}) {

//   const [teste, setTeste] = useState([]);
//   const data = useData(Config.apiUrl+"getJogadores");
//   // const data = useData("http://192.168.34.136/api/index.php?route=getJogadores");

//   useEffect(() => {
//     // Set the state only once during the initial render
//     if (data.data.length > 0) {
//       console.log(data);
//       setTeste(data.data[0].nome);
//     }
//   }, [data]);

//   return (
//     <>
//       <HeaderBar screen={"Profile"} />
//       <ScrollView>
//         <View style={styles.container}>
//           <Text style={styles.title}>Profile</Text>
//           <Image
//             style={styles.img}
//             source={require('../../assets/img/brasil.jpg')}
//           />
//           <Text>------------------------------------</Text>
//           {teste && (
//             <View>
//               <Text>Data: {teste}</Text>
//             </View>
//           )}
//         </View>
//         </ScrollView>
//     </>    
//   );
// }


// const styles = StyleSheet.create({
//   container: {
//     flex: 1,
//     backgroundColor: 'white',
//     alignItems: 'center',
//     padding: 30
//   },

//   title: {
//     fontSize: 22,
//     fontWeight: 'bold',
//     color: 'black'
//   },
  
//   img: {
//     width: '100%',
//     height: 250
//   }
// });