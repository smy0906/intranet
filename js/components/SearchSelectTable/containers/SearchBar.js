import { connect } from 'react-redux'
import { setFilter } from '../actions'
import SearchBar from '../components/SearchBar';

const mapStateToProps = (state) => {
  return {
    dataField: state.filter.dataField,
    op: state.filter.op,
    param: state.filter.param,
  }
};

const mapDispatchToProps = (dispatch, ownProps) => ({
  onChangeDataField: (e) => dispatch(setFilter({dataField:e.target.value})),
  onChangeOp: (e) => dispatch(setFilter({op:e.target.value})),
  onChangeParam: (e) => dispatch(setFilter({param:e.target.value})),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(SearchBar);
