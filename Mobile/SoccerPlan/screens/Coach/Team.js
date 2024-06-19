// import { Weight } from 'lucide-react';
import { StyleSheet, View, Text, Button, ScrollView, Dimensions, TouchableHighlight, TextInput, Image, FlatList, TouchableOpacity} from 'react-native';
import { useState, useEffect } from 'react';
import Icon from 'react-native-vector-icons/Feather';
import { format } from 'date-fns';

import HeaderBar from '../../components/HeaderBar/HeaderBar';
import Refresh from '../../components/HeaderBar/Refresh';
import JoinClub from '../../components/JoinClub';
import JoinTeam from '../../components/JoinTeam';
import showToast from '../../components/Toast/Toast';
import SelectComponent from '../../components/Selection/SelectComponent';
import CategoryList from '../../components/CategoryList';
import List from '../../components/List';
import StatsInfo from '../../components/StatsInfo';
import Config from '../../getPostApi/config';
import useData from '../../getPostApi/useData';
import sendDataToApi from '../../getPostApi/sendDataToApi';
import SegmentedControl from '../../components/SegmentControlComponent';
// import { setLoggedUser } from '../../components/Context/UserContext';
// import { useUser } from './components/Context/UserContext';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useUser } from '../../components/Context/UserContext';
import { useRefresh } from '../../components/Context/RefreshContext';
import { useBoolValues } from '../../components/Context/TeamContext';

const Team = ((userData) => {
  // console.log('Teams: ', userData);
  const userLogged = userData.userData;
  const { setLoggedUser } = useUser();
  const { dataUser } = useUser();
  const { homeTeam, categoryList, setBoolValues } = useBoolValues();
  // has a club or a team
  const [hasClub, setHasClub] = useState(false);
  const [hasTeam, setHasTeam] = useState(false);
  // wants to join a club or a team
  const [isJoinClub, setIsJoinClub] = useState(false);
  const [isJoinTeam, setIsJoinTeam] = useState(false);
  // save the value of the code club/team
  const [codeClub, setCodeClub] = useState('');
  const [codeTeam, setCodeTeam] = useState('');
  // type of request to enter the club/team
  const [typeRequest, setTypeRequest] = useState('');
  // get the value of selected club/team to enter
  const [selectedClub, setSelectedClub] = useState();
  const [selectedTeam, setSelectedTeam] = useState();

  const [currentDateTime, setCurrentDateTime] = useState(null);

  // const [isRefreshing, setIsRefreshing] = useState(false);
  const { isRefreshPressed, setRefreshPressed } = useRefresh();

  const [statsTeam, setStatsTeam] = useState(false);
  const [showMembers, setShowMembers] = useState(false);
  const [ avatars, setAvatars ] = useState();
  // const [ avatarsToShow, setAvatarsToShow] = useState();
  const [ remainingAvatarsCount, setRemainingAvatarsCount] = useState();
  const [ numberOfRows, setNumberOfRows ] = useState();
  const [ teamData, setTeamData ] = useState();

  // const [dataCoach, setDataCoach] = useState(null);
  
  const dataClub = useData(Config.apiUrl+"Clubs?route=getClubs").data;
  // console.log(dataClub.data);
  const dataTeam = useData(Config.apiUrl+"Teams?route=getTeams").data;
  // const avatars = useData(Config.apiUrl+"Teams?route=getAvatars").data;

  const getInitialsAvatar = (username) => {
    const initials = username
      .split(' ')
      .map((word) => word.charAt(0).toUpperCase())
      .join('');
    return initials;
  };

  useEffect(() => {

    if(dataUser !== null){
      if(dataUser.idClub === "" || dataUser.idClub === null){
        setHasClub(false);
      } else {
        setHasClub(true);
      }
      if(dataUser.idTeam === "" || dataUser.idTeam === null){
        setHasTeam(false);
      } else {
        setHasTeam(true);
      }
    }
    
  }, [dataUser]);

  useEffect(() => {
    if(hasTeam){
      const getAvatars = async () => {
          const apiResponse = await sendDataToApi('Teams', 'getAvatars', {"idTeam": dataUser.idTeam});
          console.log('Avatars', apiResponse.status);
          if(apiResponse.data != null){
            setAvatars(apiResponse.data);
            setNumberOfRows(apiResponse.data.length);
          } else {
            setNumberOfRows(0);
          }
          // const maxAvatarsToShow = 3;
          // setAvatarsToShow(apiResponse.data.slice(0, maxAvatarsToShow));
          // setRemainingAvatarsCount(avatars.length - maxAvatarsToShow);

          const apiResponse1 = await sendDataToApi('Teams', 'getTeamData', {"idTeam": dataUser.idTeam});
          console.log('Status: ',apiResponse1.status);
          if(apiResponse1.status == '200'){
            console.log('Team data: ',apiResponse1.data);
            setTeamData(apiResponse1.data);
          } else {
            console.log('Message: ', apiResponse1.message);
          }
      }
      getAvatars();
    }
  }, [hasTeam, isRefreshPressed]);

  const sendRequestToClub = () => {
    // get date time of when button pressed


    // const now = new Date();
    // const formatteDateTime = format(now, 'yyyy-MM-dd HH:mm:ss');
    // setCurrentDateTime(formatteDateTime);


    // console.log('Data Coach: ',dataCoach);

    const sendRequest = async () => {
      const dataRequestClub = {
        'idRequester': dataUser.idUser, 
        'idClub': selectedClub
      };  
      
      // Join the club with code
      if(typeRequest === 'Code'){
          // console.log('idClub', selectedClub, ' code', codeClub, ' idCoach', dataUser.idCoach, ' idUser', dataUser.idUser);
          const apiResponse = await sendDataToApi('Codes', 'sendCodeClub', {'idClub': selectedClub, 'code': codeClub, 'idCoach': dataUser.idCoach, 'idUser': dataUser.idUser });
          if(apiResponse.status === '400'){
              // console.log(apiResponse.message);
              showToast('error', 'Error', apiResponse.message);
          }else if(apiResponse.status ==='410'){
              showToast('error', 'Error', apiResponse.message);
          }else if(apiResponse.status ==='420'){
              showToast('error', 'Error', apiResponse.message);
          }else if(apiResponse.status ==='500'){
              showToast('error', 'Error', apiResponse.message);
          }

          if(apiResponse.status === '200'){
            showToast('success', 'Success', apiResponse.message);
            // if(apiResponse.data.idCoach){
            //   setLoggedUser({idCoach: apiResponse.data.idCoach, age: apiResponse.data.age, nacionality: apiResponse.data.nacionality, 
            //     img: apiResponse.data.img, idClub: apiResponse.data.idClub, 
            //     idUser: apiResponse.data.idUser, idClothingSize: apiResponse.data.idClothingSize, typeUser: apiResponse.data.typeUser, 
            //     email: apiResponse.data.email, firstName: apiResponse.data.firstName, lastName: apiResponse.data.lastName, 
            //     username: apiResponse.data.username});
            // }
            setIsJoinClub(false);
            setHasClub(true);
            setCodeTeam('');
        }
      
        // Make a request to join the club
      } else if(typeRequest === 'Request') {
            // const apiResponse = await sendDataToApi('Requests', 'sendRequestByCoachToEnterClub', dataNotificationClub);

            const apiResponse = await sendDataToApi('Requests', 'sendRequestClub', dataRequestClub);
            // console.log(dataNotificationClub);
            // console.log('Api response: ',apiResponse);

            if(apiResponse.status ==='400'){
                showToast('error', 'Error', apiResponse.message);
            }
            if(apiResponse.status ==='410'){
                showToast('error', 'Error', apiResponse.message);
            }

            if(apiResponse.status === '200'){
              showToast('success', 'Success', apiResponse.message);
              setIsJoinClub('false');
            }
        } 
        // else if(typeRequest === '') {
        //   showToast('error', 'Error', 'Type Request must be selected!');
        // }
        // console.log('Request failed!');
    }
    sendRequest();
  }

  const sendRequestToTeam = () => {
    // const now = new Date();
    // const formatteDateTime = format(now, 'yyyy-MM-dd HH:mm:ss');
    // setCurrentDateTime(formatteDateTime);

    const sendRequest = async () => {
      // const dataNotificationTeam = {
      //   'idTeam': selectedTeam, 
      //   'idPlayer': dataUser.idPlayer, 
      //   'idUser': dataUser.idUser,
      //   'content': 'I want to enter in the Team!',
      //   'dateTimeSended': currentDateTime,
      //   'typeNotification': 'joinTeam',
      // };

      const dataRequestTeam = {
        'idRequester': dataUser.idUser, 
        'idClub': dataUser.idClub,
        'idTeam': selectedTeam
      };
      
        if(typeRequest === 'Code'){
            const apiResponse = await sendDataToApi('Codes', 'sendCodeTeam', {'idClub': dataUser.idClub, 'idTeam': selectedTeam, 'code': codeTeam, 'idCoach': dataUser.idCoach, 'idUser': dataUser.idUser });
            // console.log(selectedTeam);
            // console.log(apiResponse.status);
            if(apiResponse.status === '400'){
                // console.log(apiResponse.message);
                showToast('error', 'Error', apiResponse.message);
            }else if(apiResponse.status ==='410'){
                showToast('error', 'Error', apiResponse.message);
            }else if(apiResponse.status ==='420'){
                showToast('error', 'Error', apiResponse.message);
            }

            if(apiResponse.status === '200'){
                showToast('success', 'Success', apiResponse.message);
                setIsJoinTeam(false);
                setHasTeam(true);
                setCodeTeam('');
            }
        } else if(typeRequest === 'Request') {
          // console.log('Data Notification: ', dataNotificationTeam)
          const apiResponse = await sendDataToApi('Requests', 'sendRequestTeam', dataRequestTeam);
          // console.log('Api response: ',apiResponse);

          if(apiResponse.status ==='400'){
              showToast('error', 'Error', apiResponse.message);
          } else if(apiResponse.status ==='410'){
              showToast('error', 'Error', apiResponse.message);
          }

          if(apiResponse.status === '200'){
            showToast('success', 'Success', apiResponse.message);
            setIsJoinTeam(false);
          }
        } else {
          showToast('error', 'Error', 'Type Request must be selected!');
        }
    }
    sendRequest();
  }

  const onJoinClubPressed = () => {
    setIsJoinClub(true);
  }

  const onCancelJoinClubPressed = () => {
    setIsJoinClub(false);
    setSelectedClub('');
    setTypeRequest('');
    setCodeClub('');
  }

  const onJoinTeamPressed = () => {
    setIsJoinTeam(true);
  }

  const onCancelJoinTeamPressed = () => {
    setIsJoinTeam(false);
    setSelectedTeam('');
    setTypeRequest('');
    setCodeTeam('');
  }

  const handleTypeSelectedClub = (selectedSegment) => {
    setTypeRequest(selectedSegment);
    // console.log(typeRequest);
  }

  const handleTypeSelectedTeam= (selectedSegment) => {
    setTypeRequest(selectedSegment);
    // console.log("Type of Request",selectedSegment);
  }

  const handleRefreshPressed = () => {
    setRefreshPressed(true);
    setTimeout(() => {
      setRefreshPressed(false);
      setBoolValues(homeTeam, false);
    }, 2000);
  }

  const handleShowMembers = () => {
    setShowMembers(!showMembers);
    setBoolValues(!homeTeam, categoryList);
    // console.log("Members: ", avatars);
  };

  const handleCancelShowMembers = () => {
    setShowMembers(!showMembers);
    setBoolValues(!homeTeam, categoryList);
  };

  const truncateString = (str, maxLength) => {
    if (str.length > maxLength) {
      return str.substring(0, maxLength) + '...';
    }
    return str;
  };


  return(
    <>
    <HeaderBar userLogged={dataUser} screen={"Team"} onRefreshPressed={handleRefreshPressed}/>
    <View style={styles.container}>
    {hasTeam && hasClub && !isRefreshPressed &&(
    <>
    {homeTeam &&(
      <>
        {!categoryList &&(
          <TouchableOpacity onPress={() => handleShowMembers()}>
          <View style={styles.containerCard}>
            <View style={styles.card}>
              <View style={styles.cardLeft}>
                <Text style={styles.txtNumber}>{numberOfRows}</Text>
              </View>
              <View style={styles.cardRight}>
                <View style={styles.headerCardR}>
                  <Text style={styles.titleR}>Members of </Text>
                  <Text style={styles.titleRName}>{teamData ? truncateString(teamData.nameTeam, 15) : 'Team'}</Text>
                </View>
                <View style={styles.avatarBody}>
                
                {/* avatars */}
                {avatars ? (
                  <FlatList
                    horizontal
                    data={avatars}
                    keyExtractor={(avatar, index) => `avatar-${index}`}
                    renderItem={({ item }) => {
                      // console.log("Item.img:", item.img); // Add this log statement
                      return (
                        <View style={styles.avatarContainer}>
                          {item.img ? (
                            <Image
                              source={{ uri: item.img }}
                              style={styles.avatar}
                            />
                          ) : (
                            <View style={styles.avatarPlaceholder}>
                              <Text style={styles.avatarPlaceholderText}>
                              {getInitialsAvatar(item.username)}
                            </Text>
                            </View>
                          )}
                        </View>
                      );
                    }}
                  />
                  ) : (
                    <Text style={styles.txtTimeL}>Team doesn't have players!</Text>
                  )}

                </View>
                {remainingAvatarsCount > 0 && (
                  <Text style={styles.ellipsisText}>+{remainingAvatarsCount}</Text>
                )}
              </View>
            </View>
          </View>
          </TouchableOpacity>
        )}
        
        <CategoryList teamData={teamData} />
      </>
    )}

    {showMembers &&(
        <List typeUser={'Coach'} members={avatars} title={`Members of ${teamData.nameTeam}`} type={'Members'} onCancel={handleCancelShowMembers} />
        // <List typeUser={'Coach'} members={avatars} title={'Members of Team'} type={'Members'} onCancel={handleCancelShowMembers} />
    )}

    </>
    )}
      <ScrollView>
      {isRefreshPressed ? (
        <>
          <Refresh userLogged={dataUser} />
        </>
      ) : (
        <>
        {!hasClub ? (
          <>
          {isJoinClub ? (
            <View style={styles.containerJoin}>
                <Text style={styles.tilteJoin}>Club</Text>
                <Text style={styles.textJoin}>Choose the Club to Join:</Text>
                {/* <Text style={styles.textJoinClub}>Select Club to join</Text> */}
                <SelectComponent 
                  options={dataClub} 
                  onChangeSelect={(idClub) => setSelectedClub(idClub)} 
                  text="Select a Club"
                  // label="Club"
                />
                <View style={{width: windowWidth}}>
                  <SegmentedControl onTypeSelected={handleTypeSelectedClub} options={['Request', 'Code']} />
                </View>
                {/*If the user don't have an account he will receive the code to join the club by email/sms */}
                {typeRequest === 'Code' && (
                  <>
                  <View>
                      <TextInput style={styles.input}
                      onChangeText={(text) => setCodeClub(text.toUpperCase())}
                      value={codeClub}
                      placeholder="Code"
                      placeholderTextColor="gray"
                      // textAlign='center'
                      maxLength={5}
                      />
                    <Icon name="users" size={35} color={'white'} marginTop={20} />
                </View>
                
                  </>
                )}
                <View style={styles.cardBtn}>
                    <TouchableHighlight  style={styles.btn} underlayColor={"#f50443"} onPress={sendRequestToClub}>
                        <Text style={{color: 'white'}}>Send Request</Text>
                    </TouchableHighlight>
                    <TouchableHighlight  style={[styles.btn, {marginTop: 10}]} underlayColor={"#f50443"} onPress={onCancelJoinClubPressed}>
                        <Text style={{color: 'white'}}>Cancelar</Text>
                    </TouchableHighlight>

                </View>
            </View>
          ) : (
          <>
            <JoinClub onJoinClubPressed={onJoinClubPressed} />
            <JoinTeam onJoinTeamPressed={onJoinTeamPressed} editable={false} />
          </>
          )}
        </> 
        ) : (
          <>
            {!hasTeam && (
            <>
              {isJoinTeam ? (
              <View style={styles.containerJoin}>
                  <Text style={styles.tilteJoin}>Team</Text>
                  <Text style={styles.textJoin}>Choose the Team to Join:</Text>

                  <View style={{width: windowWidth}}>
                    <SelectComponent 
                      options={dataTeam} 
                      onChangeSelect={(idTeam) => setSelectedTeam(idTeam)} 
                      text="Select a Team"
                      isTeam={true}
                      // label="Club"
                    />
                      {/* <SelectComponent 
                          options={dataTeam} 
                          onChangeSelect={(idTeam) => console.log(setSelectedTeam(idTeam))} 
                          text="Select a Team"
                          isTeam={true}
                          // label="Club"
                          // OptionComponent={OptionWithPhoto}
                      /> */}
                      <View style={{width: windowWidth}}>
                          <SegmentedControl onTypeSelected={handleTypeSelectedTeam} options={['Request', 'Code']} />
                      </View>
                      {/*If the user don't have an account he will receive the code to join the club by email/sms */}
                      {typeRequest === 'Code' && (
                          <>
                              <View>
                                  <TextInput style={styles.input}
                                  onChangeText={(text) => setCodeTeam(text.toUpperCase())}
                                  value={codeTeam}
                                  placeholder="Code"
                                  placeholderTextColor="gray"
                                  // textAlign='center'
                                  maxLength={5}
                                  />
                                  <Icon name="users" size={35} color={'white'} marginTop={20} />
                              </View>
                          </>
                      )}
                  </View>

                  <View style={styles.cardBtn}>
                      <TouchableHighlight  style={styles.btn} underlayColor={"#f50443"} onPress={sendRequestToTeam}>
                          <Text style={{color: 'white'}}>Send Request</Text>
                      </TouchableHighlight>
                      <TouchableHighlight  style={[styles.btn, {marginTop: 10}]} underlayColor={"#f50443"} onPress={onCancelJoinTeamPressed}>
                          <Text style={{color: 'white'}}>Cancelar</Text>
                      </TouchableHighlight>

                  </View>
              </View>
              ) : (
                <>
                <JoinTeam onJoinTeamPressed={onJoinTeamPressed} editable={true} />
              </>
              )}
            </>
            )}
          </>
        ) }
        </>
      )} 
  </ScrollView>
  </View>
  </>
)
});
  
const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    width: windowWidth,
    backgroundColor: 'white',
    alignItems: 'center',
    justifyContent: 'center',
  },
  containerCard: {
    flexDirection: 'column',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
    marginTop: '10%',
    alignItems: 'center',
    width: '100%'
  },
  title: {
    fontSize: 22,
    fontWeight: 'bold',
    color: 'black'
  },
  containerJoin: {
    flexDirection: 'column',
    width: windowWidth,
    height: windowHeight - 130,
    alignItems: 'center',
    // backgroundColor: 'white'
    backgroundColor: "white",
},
tilteJoin: {
    color: 'black',
    fontSize: 20,
    fontWeight: 'bold',
    marginTop: 20,
},
textJoin: {
    color: 'black',
    fontSize: 15,
    marginTop: 10
},
cardBtn: {
    width: '110%', // Adjust the width as needed based on your design
    height: 100,
    // backgroundColor: 'white',
    backgroundColor: 'white',
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 0,
    // borderTopWidth: 1,
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
    // borderWidth: 1,
    // borderColor: 'white',
    borderRadius: 10
},
input: {
  width: windowWidth,
  height: 60,
  alignItems: 'center',
  color: 'black',
  // borderWidth: 1,
  borderBottomWidth: 1,
  borderTopWidth: 1,
  borderColor: 'gray',
  marginTop: 10,
  padding: 15,
  backgroundColor: 'white'
},
card: {
  flexDirection: 'row',
  width: '100%', // Adjust the width as needed based on your design
  backgroundColor: '#041b2b',
  borderRadius: 8,
  shadowColor: '#041b2b',
  shadowOffset: {width: 3, height: 4},
  shadowOpacity: 0.4,
  shadowRadius: 2,  
},
cardLeft: {
  flexDirection: 'column',
  width: '20%',
  justifyContent: 'center',
  alignItems: 'center',
  // borderRightWidth: 1,
  // borderRightColor: 'gray',

},
cardRight: {
  width: '70%',
  marginVertical: '3%',
},
headerCardR: {
  flexDirection: 'row'
},
txtNumber:{
  fontSize: 20,
  color: 'white',
  fontWeight: 'bold',
  marginLeft: '15%'
},
txtTimeL: {
  fontSize: 13,
  color: 'white',
  marginTop: 10
},
titleR:{
  fontSize: 16,
  fontWeight: 'bold',
  color: "white",
  marginVertical: '2.5%'
},   
titleRName:{
  fontSize: 16,
  fontWeight: 'bold',
  color: "#f50443",
  marginVertical: '2.5%'
},   
txtR:{
  fontSize: 15,
  color: 'white',
  marginLeft: 10
},
// avatarBody: {
//   backgroundColor: 'black',
//   width: '80%' // Adjust the margin based on your design
// },
avatarContainer: {
  // marginRight: -15, // Adjust the margin based on your design
},

avatar: {
  // marginRight: -20,
  width: 50, // Adjust the width and height based on your design
  height: 50,
  borderRadius: 25, // Make it round
},

avatarPlaceholder: {
  width: 50, // Adjust the width and height based on your design
  height: 50,
  borderRadius: 25, // Make it round
  backgroundColor: 'gray', // Customize the placeholder color
  justifyContent: 'center',
  alignItems: 'center',
},

avatarPlaceholderText: {
  color: 'white', // Customize the text color
  fontSize: 18, // Customize the font size
  fontWeight: 'bold',
},

username: {
  marginTop: 5, // Adjust the margin based on your design
  textAlign: 'center', // Align the username text to the center
},
});

export default Team;