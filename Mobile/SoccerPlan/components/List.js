import React, { useState } from 'react';
import { View, Text, FlatList, Image, TouchableOpacity, StyleSheet, SafeAreaView, Dimensions, TextInput } from 'react-native';
import { Feather, Fontisto, SimpleLineIcons, MaterialCommunityIcons, MaterialIcons } from '@expo/vector-icons';
import { Handle } from 'tamagui';
import StatsInfo from './StatsInfo';
import SubHeader from './HeaderBar/SubHeader';
import sendDataToApi from '../getPostApi/sendDataToApi';
import showToast from './Toast/Toast';
import { useUser } from './Context/UserContext';


const List = ({ typeUser, type, value, title, members, onCancel }) => {

  const [showModalPlayer, setShowModalPlayer] = useState(false);
  const [showModalAdversary, setShowModalAdversary] = useState(false);
  const [selectedPlayer, setSelectedPlayer] = useState(null);
  const [clubName, setClubName] = useState('');
  const {dataUser} = useUser();

  if(!members){
    return(
      <View style={styles.container}>
        <Text>Loading ...</Text>
      </View>
    )
  }

    let getInitialsAvatar = (username) => {
      const initials = username
        .split(' ')
        .map((word) => word.charAt(0).toUpperCase())
        .join('');
      return initials;
    };

  const handleShow = (player) => {
    setSelectedPlayer(player);
  };

  const handleCancelShowStats = () => {
    setSelectedPlayer(null);
  };

  const handleDelete = async (player) => {
    player.idTeam = dataUser.idTeam;
    // console.log(player);

    const apiResponse = await sendDataToApi('Players', 'leaveTeam', player);
    if(apiResponse.stats == '200'){
      console.log(apiResponse.message);
    } else {
      console.log(apiResponse.message);
    }
  }

  const handleAddPlayer = () => {
    // setShow(!show);
    console.log('Add player');
  }

  const handleCreateAdversary = () => {
    const createAdversaryAsync = async () => {
      const idChampionship = value;
      const dataToSend = {
        idChampionship,
        clubName,
      };

      const apiResponse = await sendDataToApi("Adversaries", "createAdversary", dataToSend);

      if(apiResponse.status == '400'){
        showToast('error', 'Error', 'Type event must be selected');
      } else {
        // Reset TextInput Field
        setClubName("");
        showToast('success', 'Success', apiResponse.message);
      }
    }
    createAdversaryAsync();
  }

  return (
    <>
    {!selectedPlayer ? (
      // <SafeAreaView style={styles.container}>
      <>
        <SubHeader onCancel={onCancel} title={title} />
        {/* <Text style={styles.title}>{members ? title : "Team doesn't have players!"}</Text> */}
        {type == 'Members' && typeUser == 'Coach' ? (
          <TouchableOpacity onPress={() => setShowModalPlayer(true)}>
            <Feather style={styles.icon} name='user-plus' color={'#f50443'} size={25} />
          </TouchableOpacity>
        ) : type == 'Championship' && typeUser == 'Coach' &&(
            <TouchableOpacity onPress={() => setShowModalAdversary(true)}>
              <Feather style={styles.icon} name='plus-circle' color={'#f50443'} size={25} />
            </TouchableOpacity>
        )}
        <FlatList
          horizontal={false}
          data={members}
          keyExtractor={(member, index) => `member-${index}`}
          contentContainerStyle={styles.membersContainer}

          renderItem={({ item }) => (
            <View style={styles.memberContainer}>
              { type == 'StatsPlayer' ? (
                <TouchableOpacity onPress={() => handleShow(item)}>
                  <View style={styles.avatarContainer}>
                    {item.img ? (
                      <Image source={{ uri: item.img }} style={styles.avatar} />
                    ) : (
                      <View style={styles.avatarPlaceholder}>
                        <Text style={styles.avatarText}>{getInitialsAvatar(item.username)}</Text>
                      </View>
                    )}
                    <Text style={styles.memberName}>{item.username}</Text>
                  </View>
                </TouchableOpacity>

              ) : type == 'Championship' ? (
                  <View style={styles.avatarContainer}>
                    {item.img ? (
                      <Image source={{ uri: item.img }} style={styles.avatar} />
                    ) : (
                      <View style={styles.avatarPlaceholder}>
                        <Text style={styles.avatarText}>{getInitialsAvatar(item.nameClub)}</Text>
                      </View>
                    )}
                    <Text style={styles.memberName}>{item.nameClub}</Text>
                  </View>

              ) : (
                <View style={styles.avatarContainer}>
                  {item.img ? (
                    <Image source={{ uri: item.img }} style={styles.avatar} />
                  ) : (
                    <View style={styles.avatarPlaceholder}>
                      <Text style={styles.avatarText}>{getInitialsAvatar(item.username)}</Text>
                    </View>
                  )}
                  <Text style={styles.memberName}>{item.username}</Text>
                  {typeUser == 'Coach' && (
                    <TouchableOpacity onPress={() => handleDelete(item)} style={styles.deleteButton}>
                        <Feather name='trash-2' color={'white'} size={20} />
                    </TouchableOpacity>
                  )}
                </View>
                )} 
            </View>
          )}
          
        />
      </>
    ) : (

      <StatsInfo title={`Stats of ${selectedPlayer.username}`} stats={selectedPlayer} onCancel={handleCancelShowStats}/>

    )}
    <>
      {showModalPlayer && (
        <View
          style={styles.mainView}
          animationType='slide'
          transparent={true}
          visible={showModalPlayer}         
          onRequestClose={() => {
            setShowModalPlayer(!showModalPlayer);
            showToast('info', 'Info', 'Add Player canceled!');
          }}>
  
          <View style={styles.centeredView}>
            <View style={styles.topModalView}>
              <TouchableOpacity onPress ={() => setShowModalPlayer(!showModalPlayer)}>
                <Text style={{marginTop: 70, marginRight: 50, color: 'white'}}>Cancel</Text>
              </TouchableOpacity>
              <Text style={styles.titleModal}>Add Player</Text>
              <TouchableOpacity onPress ={() => handleAddPlayer()}>
                  <Text style={{marginTop: 70, marginLeft: 50, color: 'white'}}>
                    Add
                  </Text>
              </TouchableOpacity>
            </View>
            
            <View style={styles.bottomModalView}>
              <Text style={styles.titleForm}>Club Name</Text>
              <TextInput 
                style={styles.input}
                value={''}
                placeholder="Club Name"
                onChangeText={''}/>
            </View>
          </View>
        </View>
      )}

      {showModalAdversary && (
        <View
          style={styles.mainView}
          animationType='slide'
          transparent={true}
          visible={showModalAdversary}         
          onRequestClose={() => {
            setShowModalAdversary(!showModalAdversary);
            showToast('info', 'Info', 'Create Adversary canceled!');
          }}>
  
          <View style={styles.centeredView}>
            <View style={styles.topModalView}>
              <TouchableOpacity onPress ={() => setShowModalAdversary(!showModalAdversary)}>
                <Text style={{marginTop: 70, marginRight: 15, color: 'white'}}>Cancel</Text>
              </TouchableOpacity>
              <Text style={styles.titleModal}>Create Adversary</Text>
              <TouchableOpacity onPress ={() => handleCreateAdversary()}>
                  <Text style={{marginTop: 70, marginLeft: 15, color: 'white'}}>
                    Create
                  </Text>
              </TouchableOpacity>
            </View>
            
            <View style={styles.bottomModalView}>
              <Text style={styles.titleForm}>Club Name</Text>
              <TextInput 
                style={styles.input}
                value={clubName}
                placeholder="Club Name"
                onChangeText={setClubName}/>
            </View>
          </View>
        </View>
      )}
    </>
    </>
  );
};

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
  container: {
    flexGrow: 1,
    alignItems: 'center',
    backgroundColor: '#fff',
    padding: 16,
    height: '90%',  // Adjust the height as needed
  },
  btnAdd: {
    marginLeft: '2.5%',
    marginTop: 10
  },
  icon: {
    marginTop: 10
  },
  membersContainer: {
    flexGrow: 1,
    paddingVertical: 20,
    width: windowWidth - 40,
  },
  memberContainer: {
    backgroundColor: '#041b2b',
    padding: 10,
    borderRadius: 8,
    marginBottom: 10,
  },
  avatarContainer: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  avatar: {
    width: 50,
    height: 50,
    borderRadius: 25,
  },
  avatarPlaceholder: {
    width: 50,
    height: 50,
    borderRadius: 25,
    backgroundColor: 'gray',
    justifyContent: 'center',
    alignItems: 'center',
  },
  avatarText: {
    color: 'white',
  },
  memberName: {
    marginLeft: 10,
    color: 'white',
  },
  deleteButton: {
    backgroundColor: '#f50443',
    padding: 8,
    borderRadius: 5,
    marginLeft: Platform.OS == "ios" ? 160 : 180,
    marginTop: 5,
    alignItems: 'center',
    flexDirection: 'row',
  },
  cancelButton: {
    backgroundColor: '#f50443',
    padding: 12,
    borderRadius: 8,
    alignItems: 'center',
    width: '50%',
    alignSelf: 'center',
    marginTop: 10
  },
  cancelButtonText: {
    color: 'white',
    fontWeight: 'bold',
  },

   //Modal View
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
    height: 100,
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
    marginTop: 65,
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
});

export default List;
