import { Fragment } from "react";
import Vacation from "../Components/Vacation";
import classes from "../Components/Vacation.module.css";
import { DUMMY_VACATIONS } from "../shared/dummyData";



const VacationList = () => {
    const vacationRemoveHandler = (id) => {
        console.log(id);
    };
    const editVacationHandler = (id) => {
        console.log(id);
    };


    const vacationList = DUMMY_VACATIONS.map(vacation => <Vacation
        key={vacation.id}
        name={vacation.name}
        description={vacation.description}
        image={vacation.image}
        dates={vacation.dates}
        price={vacation.price}
        onEdit={editVacationHandler.bind(null, vacation.id)}
        onRemove={vacationRemoveHandler.bind(null, vacation.id)}
    />);
  return(<Fragment>
      <div className={classes.container}>
          {vacationList}
      </div>

  </Fragment>);
}

export default VacationList;