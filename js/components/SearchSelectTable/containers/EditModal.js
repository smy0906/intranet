import { connect } from 'react-redux'
import { closeModalAndUpsert, closeModal, addRow, editRow } from '../actions'
import EditModal from '../components/EditModal';

const mapStateToProps = (state) => {
  return {
    data: state.modal.data,
    isOpen: state.modal.isOpen,
    editMode: state.modal.editMode,
    rowIndex: state.modal.rowIndex
  }
};

const mapDispatchToProps = (dispatch, ownProps) => ({
  onClose: (isConfirm) => {dispatch(closeModal(isConfirm))}
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(EditModal);
