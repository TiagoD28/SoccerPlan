import { StyleSheet, View, Text, Button, TouchableOpacity, TextInput, Modal, Platform, ScrollView, Dimensions} from 'react-native';
// import {Card, Avatar} from 'react-native-paper'; 
import React, { useState, useRef, useEffect } from 'react';
import Icon from 'react-native-vector-icons/Feather';
import showToast from '../../components/Toast/Toast';
import DropDownComponent from '../../components/DropDownComponent';
import DateTimePickerComponent from '../../components/DateTimePickerComponent';
import CardComponents from '../../components/CardComponents';
import useData from '../../getPostApi/useData';
import Config from '../../getPostApi/config';
import sendDataToApi from '../../getPostApi/sendDataToApi';
import HeaderBar from '../../components/HeaderBar/HeaderBar';
import Refresh from '../../components/HeaderBar/Refresh';
import { useUser } from '../../components/Context/UserContext';
import { useRefresh } from '../../components/Context/RefreshContext';

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

// export default function Calendar({userData}){
// export default function Calendar(userData){
export default function Calendar({userData}){
  // i must use the parameter {userData}, but i can t use because its with the same name that useUser();

  const userLogged = userData;
  const { dataUser } = useUser();
  // console.log(userLogged);
  const [modalVisible, setModalVisible] = useState(false);
  const [typeEvent, setTypeEvent] = useState("");
  const [local, setLocal] = useState("");
  const [meetingLocal, setMeetingLocal] = useState("");
  const [startDate, setStartDate] = useState("");
  const [endDate, setEndDate] = useState("");
  const [meetTime, setMeetTime] = useState("");
  const [idUser, setIdUser] = useState(dataUser.idUser);
  const [idClub, setIdClub] = useState(dataUser.idClub);
  const [idTeam, setIdTeam] = useState(dataUser.idTeam);
  const [hasClub, setHasClub] = useState(false);
  const [isRefreshing, setIsRefreshing] = useState(false);
  const { isRefreshPressed, setRefreshPressed } = useRefresh();

  const dataDropDown = [
    { label: 'Game', value: '1' },
    { label: 'Practice', value: '2' },
    { label: 'Event', value: '3' },
  ];

  // console.log(useData(Config.apiUrl+"Events?route=getEvents").data);
  console.log('Data User: ',dataUser);
  const addEvent = () => {
    // setIdUser(dataUser.idUser);
    // setIdClub(dataUser.idClub);
    const addEventAsync = async () => {
      const dataToSend = {
        idUser,
        idClub,
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
        showToast('error', 'Error', 'Type event must be selected');
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

  const handleRefreshPressed = () => {
    setRefreshPressed(true);
    setTimeout(() => {
      setRefreshPressed(false);
    }, 2000);
  }

  // console.log("User lOgged: ",userLogged);
  return (
    <>
      {/* <HeaderBar screen={'Calendar'} marginLeft={'25%'} marginRight={'25%'} /> */}
      {/* <HeaderBar userLogged={userLogged} screen={"Calendar"} onRefreshPressed={handleRefreshPressed}/> */}
      <HeaderBar userLogged={dataUser} screen={"Calendar"} onRefreshPressed={handleRefreshPressed}/>
        <ScrollView>
          {/* <CardComponents cards={useData("http://192.168.0.10/api/index.php?route=getEvents").data}/> */}
          {/* <CardComponents cards={useData(Config.apiUrl+"Events?route=getEvents").data} userLogged={userLogged}/> */}
          {isRefreshPressed ? (
            // <Refresh userLogged={userLogged} /> it must be this and not the down
            <Refresh userLogged={dataUser} />
            // <><Text>Ola</Text></>
          ) : (
            // <CardComponents cards={useData(Config.apiUrl+"Events?route=getEvents").data}/>
            <CardComponents />
            // <Text>Ola 1</Text>
          )}
        </ScrollView>

        <TouchableOpacity
          style={styles.btnPlus}
          onPress={() => setModalVisible(!modalVisible)}
        >
          <Icon name="plus" size={24} color="white" />
        </TouchableOpacity>

      {/* Modal View */}   
      {modalVisible && (
      <View
        style={styles.mainView}
        animationType='slide'
        transparent={true}
        visible={modalVisible}         
        onRequestClose={() => {
          setModalVisible(!modalVisible);
          showToast('info', 'Info', 'Add event canceled!');
        }}>

        <View style={styles.centeredView}>
          <View style={styles.topModalView}>
            <TouchableOpacity onPress ={() => setModalVisible(!modalVisible)}>
              <Text style={{marginTop: 50, color: 'white'}}>Cancel</Text>
            </TouchableOpacity>
            <Text style={styles.title}>Create Event</Text>
            <TouchableOpacity onPress ={() => addEvent()}>
                <Text style={{marginTop: 50, color: 'white'}}>
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
      )}
    {/* </View> */}
    </>
  );
}

const styles = StyleSheet.create({
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
   //Modal View
  mainView: {
    flex: 1,
    position: 'absolute',
    marginTop: 40,
    marginLeft: Platform.OS == "ios" ? -10 : 0,
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
    width: Platform.OS == "ios" ? 377 : 392.5,
    height: 100,
    backgroundColor: '#041b2b',
    borderTopRightRadius: 20,
    borderTopLeftRadius: 20
  },
  title: {
    fontSize: 22,
    fontWeight: 'bold',
    color: 'white',
    marginLeft: 70,
    marginRight: 75,
    marginTop: 45,
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
})