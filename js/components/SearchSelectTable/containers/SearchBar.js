import { connect } from 'react-redux'
import { setFilter } from '../actions'
import SearchBar from '../components/SearchBar';

const mapStateToProps = (state) => {
  return {
    options: state.filter.options,
    selected: state.filter.selected,
    //param: state.filter.param,
    op: state.filter.op,
  }
};

const mapDispatchToProps = (dispatch, ownProps) => ({
  onChangeDataField: (e) => dispatch(
    setFilter({
      selected: e.target.value,
      param1:undefined,
      param2:undefined
    })),
  onChangeOp: (e) => dispatch(setFilter({op: e.target.value})),
  onChangeParam1: (value) => dispatch(setFilter({param1: value})),
  onChangeParam2: (value) => dispatch(setFilter({param2: value})),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(SearchBar);
