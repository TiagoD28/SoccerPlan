// import { Weight } from 'lucide-react';
import { StyleSheet, View, Text, Button, Image, ScrollView, Dimensions, ImageBackground} from 'react-native';
import { useState, useEffect, useRef } from 'react';
import { TouchableOpacity } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { Feather } from '@expo/vector-icons';
import ImgToBase64 from 'react-native-image-base64';

import Config from '../../getPostApi/config';
import useData from '../../getPostApi/useData';
import HeaderBar from '../../components/HeaderBar/HeaderBar';
import SubHeader from '../../components/HeaderBar/SubHeader';
import Settings from '../../components/HeaderBar/Settings';
import EditProfile from '../../components/EditProfile';
import Refresh from '../../components/HeaderBar/Refresh';
import { useTheme } from '../../components/Context/ThemeContext';
import { useRefresh } from '../../components/Context/RefreshContext';
import { useUser } from '../../components/Context/UserContext';
import { set } from 'date-fns';


export default function Profile({userData, setIsLogged}) {
  const userLogged = userData;
  const [edit, setEdit] = useState(false);
  const [isSettings, setIsSettings] = useState(false);
  const [theme] = useTheme();
  const containerStyle = theme === 'dark' ? styles.darkContainer : styles.lightContainer;
  const { isRefreshPressed, setRefreshPressed } = useRefresh();
  const { dataUser } = useUser();
  const [imageBase64, setImageBase64] = useState(null);
  const [decodedImage, setDecodedImage] = useState('');

  // useEffect(() => {
  //   // Decode the base64-encoded image string
  //   // setImageBase64(`data:image/png;base64,${base64Image}`);
  //   const imageUrl = dataUser.img;
  //   console.log(dataUser.img);
  //   ImgToBase64.getBase64String(imageUrl)
  //     .then((base64String) => {
  //       setDecodedImage(`data:image/png;base64,${base64String}`);
  //     })
  //     .catch((error) => {
  //       console.error('Error decoding image:', error);
  //     });
  // }, []);


  // const navigation = useNavigation();

  const cancelSettings = () => {
    setIsSettings(!isSettings);
  }

  const handleCancelEdit = () => {
    setEdit(!edit);
  }

  const handleRefreshPressed = () => {
    console.log('Refreshing...');
    setRefreshPressed(true);
    setTimeout(() => {
      console.log('Refreshed!');
      setRefreshPressed(false);
    }, 2000);
    // console.log('Refreshed');
  }

  return (
    <>
      {/* <HeaderBar screen={"Profile"} onSettingsPressed={onSettingsPressed} /> */}
      <HeaderBar userLogged={dataUser} screen={"Profile"} onSettingsPressed={() => setIsSettings(!isSettings)} onRefreshPressed={handleRefreshPressed} />
      {edit &&(
        <SubHeader onCancel={handleCancelEdit} title='Edit Profile'/>
      )}
      <View>
      {/* Display the decoded image */}
      {decodedImage && <Image source={{ uri: decodedImage }} style={{ width: 200, height: 200 }} />}
    </View>
      <ScrollView>
        {isRefreshPressed ? (
          <>
          {/* Call Refresh and send data to Refresh */}
            <Refresh userLogged={dataUser} /> 
            {/* <Refresh /> */}
          </>
        ) : (
          <>
            {isSettings ? (
            <>
              <Settings setIsLogged={setIsLogged} userLogged={dataUser} cancelSettings={cancelSettings} />
            </>
            ) : (
            <>
              <View style={containerStyle}>
              {edit ? (
                <EditProfile onCancel={handleCancelEdit} />
              ) : (
                <>
                  {/* <TouchableOpacity style={styles.btnImg} onPress={() => setEdit(true)}>
                    <Image 
                      style={styles.img}
                      source={{uri: 'https://afatv.pt/img/jogadores/HUGO-OLIVEIRA.png'}}
                    />
                  </TouchableOpacity> */}
                  <TouchableOpacity style={styles.btnImg} onPress={() => setEdit(true)}>
                  <ImageBackground style={styles.img} source={{ uri: imageBase64 }}>
                    <Feather style={styles.icon} name='edit' color={'#f50443'} size={25} />
                    </ImageBackground>
                  </TouchableOpacity>
                  <Text style={{marginTop: 10}}>{dataUser.firstName + ' ' + dataUser.lastName}</Text>
                  <Text style={{marginTop: 10}}>{dataUser.typeUser}</Text>
                  <View style={styles.cardContainer}>
                    <View style={styles.cardInfo}>
                      <View style={styles.cardLeft}>
                        <Text style={styles.titleCard}>Age:</Text>
                        <Text style={styles.txtCard}>{dataUser.age ? dataUser.age : '-'}</Text>
                      </View>
                      <View style={styles.cardCenter}>
                        <Text style={styles.titleCard}>Nationality:</Text>
                        <Text style={styles.txtCard}>{dataUser.nacionality ? dataUser.nacionality : '-'}</Text>
                      </View>
                      <View style={styles.cardRigth}>
                        <Text style={styles.titleCard}>Phone: </Text>
                        <Text style={styles.txtCard}>{dataUser.phoneNumber ? dataUser.phoneNumber : '-'}</Text>
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
  icon: {
    marginLeft: 70,
    marginTop: 70,
    opacity: 0.5
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