import { StyleSheet, View, Text, Button, TouchableOpacity, TextInput, Modal, Platform, ScrollView, Dimensions} from 'react-native';
// import {Card, Avatar} from 'react-native-paper'; 
import React, { useState, useRef, useEffect } from 'react';
import Icon from 'react-native-vector-icons/Feather';
import { Feather, Fontisto, SimpleLineIcons, MaterialCommunityIcons, MaterialIcons } from '@expo/vector-icons';

import showToast from '../../components/Toast/Toast';
import DropDownComponent from '../../components/DropDownComponent';
import DateTimePickerComponent from '../../components/DateTimePickerComponent';
import CardComponents from '../../components/CardComponents';
import useData from '../../getPostApi/useData';
import Config from '../../getPostApi/config';
import sendDataToApi from '../../getPostApi/sendDataToApi';
import HeaderBar from '../../components/HeaderBar/HeaderBar';
import SubHeader from '../../components/HeaderBar/SubHeader';
import Refresh from '../../components/HeaderBar/Refresh';
import { useUser } from '../../components/Context/UserContext';
import { useRefresh } from '../../components/Context/RefreshContext';

// export default function Calendar({userData}){
// export default function Calendar(userData){
export default function Calendar({userData}){
  // i must use the parameter {userData}, but i can t use because its with the same name that useUser();

  // const userLogged = userData;
  const { dataUser } = useUser();
  // console.log(userLogged);
  const [modalVisible, setModalVisible] = useState(false);
  const [typeEvent, setTypeEvent] = useState("");
  const [local, setLocal] = useState("");
  const [meetingLocal, setMeetingLocal] = useState("");
  const [startDate, setStartDate] = useState("");
  const [endDate, setEndDate] = useState("");
  const [meetTime, setMeetTime] = useState("");
  // const [idUser, setIdUser] = useState();
  // const [idClub, setIdClub] = useState();
  // const idTeam = useState();
  // const [hasClub, setHasClub] = useState(false);
  const { isRefreshPressed, setRefreshPressed } = useRefresh();

  const dataDropDown = [
    { label: 'Game', value: '1' },
    { label: 'Practice', value: '2' },
    { label: 'Event', value: '3' },
  ];

  // console.log('Data User: ',dataUser);
  const addEvent = () => {
    // idUser = dataUser.idUser;
    // idClub = dataUser.idClub;
    // idTeam = dataUser.idTeam;
    const addEventAsync = async () => {
      
      const dataToSend = {
        'idUser': dataUser.idUser,
        'idClub': dataUser.idClub,
        'idTeam': dataUser.idTeam,
        typeEvent,
        startDate,
        endDate,
        meetTime,
        local,
        meetingLocal,
      };

      console.log("Data to send : ", dataToSend);
  
      const apiResponse = await sendDataToApi("Events", "addEvent", dataToSend);

      if(apiResponse.status == '400' || apiResponse.status == '500'){
        console.log(apiResponse.message);
        showToast('error', 'Error', apiResponse.message);
      } else {
        console.log(apiResponse.message);
        // Reset TextInput Fields
        setTypeEvent(null);
        setStartDate("");
        setEndDate("");
        setMeetTime("");
        setLocal("");
        setMeetingLocal("");
        showToast('success', 'Success', apiResponse.message);
      }
    }
    addEventAsync();
  }

  // useEffect(() => {
    
  // }, [isRefreshPressed, dataUser])

  const handleRefreshPressed = () => {
    setRefreshPressed(true);
    setTimeout(() => {
      setRefreshPressed(false);
    }, 2000);
  }

  // console.log("User lOgged: ",userLogged);
  return (
    <>
      <HeaderBar userLogged={dataUser} screen={"Calendar"} onRefreshPressed={handleRefreshPressed}/>
      {/* {modalVisible &&(
        <SubHeader onCancel={() => setModalVisible(!modalVisible)} title={'Create Event'} />
      )} */}
        <ScrollView>
          {isRefreshPressed ? (
            <Refresh userLogged={dataUser} />
            ) : !modalVisible && (
              <CardComponents />
              )}
              </ScrollView>
      {/* Modal View */}   
      {modalVisible && (
        <View
        style={styles.mainView}
        animationType='slide'
        transparent={true}
        visible={modalVisible}         
        onRequestClose={() => {
          setModalVisible(!modalVisible);
          showToast('info', 'Info', 'Add Event canceled!');
        }}>

        <View style={styles.centeredView}>
          <View style={styles.topModalView}>
            <TouchableOpacity onPress ={() => setModalVisible(!modalVisible)}>
              <Text style={{marginTop: 30, marginRight: 35, color: 'white'}}>Cancel</Text>
            </TouchableOpacity>
            <Text style={styles.titleModal}>Create Event</Text>
            <TouchableOpacity onPress ={() => addEvent()}>
                <Text style={{marginTop: 30, marginLeft: 35, color: 'white'}}>
                  Create
                </Text>
            </TouchableOpacity>
          </View>
          
          <View style={styles.bottomModalView}>
          <Text style={styles.titleForm}>Type</Text>
            <DropDownComponent data={dataDropDown} onDropDownChange={setTypeEvent} />
            <Text style={styles.titleForm}>Date</Text>
            <DateTimePickerComponent id={'startDate'} mode={'date'} placeholder={'Start Date'} onDateTimePickerChange={setStartDate} />
            <DateTimePickerComponent id={'endDate'} mode={'date'} placeholder={'End Date'} onDateTimePickerChange={setEndDate} />
            <DateTimePickerComponent id={'meetingTime'} mode={'time'} placeholder={'Meeting Time'} onDateTimePickerChange={setMeetTime} />
            <Text style={styles.titleForm}>Local</Text>
            <TextInput 
              style={styles.input}
              value={local}
              placeholder="Local"
              onChangeText={setLocal}/>
            <TextInput 
              style={styles.input}
              value={meetingLocal}
              placeholder="Meeting Local"
              onChangeText={setMeetingLocal}/>
          </View>
        </View>
      </View>
        // <View>
        //   <View style={styles.centeredView}>          
        //     <View style={styles.bottomModalView}>
        //       <Text style={styles.titleForm}>Type</Text>
        //       <DropDownComponent data={dataDropDown} onDropDownChange={setTypeEvent} />
        //       <Text style={styles.titleForm}>Date</Text>
        //       <DateTimePickerComponent id={'startDate'} mode={'date'} placeholder={'Start Date'} onDateTimePickerChange={setStartDate} />
        //       <DateTimePickerComponent id={'endDate'} mode={'date'} placeholder={'End Date'} onDateTimePickerChange={setEndDate} />
        //       <DateTimePickerComponent id={'meetingTime'} mode={'time'} placeholder={'Meeting Time'} onDateTimePickerChange={setMeetTime} />
        //       <Text style={styles.titleForm}>Local</Text>
        //       <TextInput 
        //         style={styles.input}
        //         value={local}
        //         placeholder="Local"
        //         onChangeText={setLocal}/>
        //       <TextInput 
        //         style={styles.input}
        //         value={meetingLocal}
        //         placeholder="Meeting Local"
        //         onChangeText={setMeetingLocal}/>
        //     </View>
        //   </View>
        // </View>
      )}
    {/* </View> */}
      {dataUser.idClub == null ? 
        <TouchableOpacity
          style={styles.btnPlus}
          onPress={() => {
              setModalVisible(!modalVisible);
          }}
        >
          <Icon name="plus" size={25} color="white" />
        </TouchableOpacity>
      :
        <TouchableOpacity
        style={styles.btnPlusOpacity}
        onPress={''}
        disabled={true}
        >
          <Icon name="plus" size={25} color="white" />
        </TouchableOpacity>
      }
    </>
  );
}

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
  mainView: {
    flex: 1,
    position: 'absolute',
    // marginTop: '100%',
    // marginLeft: Platform.OS == "ios" ? -10 : 0,
    backgroundColor: '#041b2b'
  },
  centeredView: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  topModalView: {
    flexDirection: 'row',
    justifyContent: 'center',
    // alignItems: 'center',
    width: windowWidth,
    height: 60,
    backgroundColor: '#041b2b',
    borderTopRightRadius: 20,
    borderTopLeftRadius: 20,
    marginTop: '10%'
  },
  titleModal: {
    fontSize: 22,
    fontWeight: 'bold',
    color: 'white',
    marginLeft: 40,
    marginRight: 45,
    marginTop: 25,
  },
  bottomModalView: {
    width: 393,
    height: 760,
    backgroundColor: 'white',
    shadowColor: '#000',
    padding: 10,
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 5,
  },
  titleForm: {
    marginLeft: 15,
    fontSize: 20,
    fontWeight: 'bold',
  },
  button: {
    borderRadius: 20,
    padding: 10,
    elevation: 2,
  },
  textStyle: {
    color: 'white',
    fontWeight: 'bold',
    textAlign: 'center',
  },
  modalText: {
    marginBottom: 15,
    textAlign: 'center',
  },
  input: {
    height: 40,
    margin: 12,
    borderWidth: 1,
    borderRadius: 10,
    padding: 10,
},
  btnPlus : {
    position: "absolute",
    bottom: 20,
    right: 20,
    height: Platform.OS == "ios" ? 50 : 60,
    width: Platform.OS == "ios" ? 50 : 60,
    borderRadius: Platform.OS == "ios" ? 25 : 30,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#f50443',
  },
  btnPlusOpacity : {
    position: "absolute",
    bottom: 20,
    right: 20,
    height: Platform.OS == "ios" ? 50 : 60,
    width: Platform.OS == "ios" ? 50 : 60,
    borderRadius: Platform.OS == "ios" ? 25 : 30,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#f50443',
    opacity: 0.5
  },

  header: {
    backgroundColor: '#041b2b',
    width: windowWidth,
    flexDirection: 'row',
    padding: 10,
  },
  title1: {
    fontSize: 15,
    color: 'white',
    marginLeft: 10, // Add some margin for spacing between the icon and title
  },

   //Modal View
  // mainView: {
  //   flex: 1,
  //   position: 'absolute',
  //   marginTop: 40,
  //   // marginLeft: Platform.OS == "ios" ? -10 : 0,
  // },
  centeredView: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  title: {
    fontSize: 22,
    fontWeight: 'bold',
    color: 'white',
    marginLeft: 60,
    marginRight: 65,
    marginTop: 45,
  },
  bottomModalView: {
    width: windowWidth,
    height: windowHeight,
    backgroundColor: 'white',
    shadowColor: '#000',
    padding: 10,
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 5,
  },
  titleForm: {
    marginLeft: 15,
    fontSize: 20,
    fontWeight: 'bold',
  },
  button: {
    borderRadius: 20,
    padding: 10,
    elevation: 2,
  },
  textStyle: {
    color: 'white',
    fontWeight: 'bold',
    textAlign: 'center',
  },
  modalText: {
    marginBottom: 15,
    textAlign: 'center',
  },
  input: {
    height: 40,
    margin: 12,
    borderWidth: 1,
    borderRadius: 10,
    padding: 10,
},
})